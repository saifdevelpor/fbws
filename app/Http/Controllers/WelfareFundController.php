<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\WelfareMonth;
use App\Models\WelfareTransaction;
use App\Models\AuditLog; // ✅ ADD
use App\Services\WelfareLedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class WelfareFundController extends Controller
{
    private function audit(string $event, string $action, string $auditableType, ?int $auditableId, $old = null, $new = null): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'module' => 'WelfareFund',
            'action' => $action,
            'auditable_type' => $auditableType,
            'auditable_id' => $auditableId,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);
    }

    public function index(Request $request)
    {
        $month = (int) ($request->get('month') ?? now()->month);
        $year  = (int) ($request->get('year') ?? now()->year);

        $wMonth = $this->getOrCreateMonth($month, $year);

        $transactions = WelfareTransaction::with(['creator:id,name'])
            ->where('welfare_month_id', $wMonth->id)
            ->latest()
            ->get();

        $monthName = Carbon::createFromDate($year, $month, 1)->format('F');

        $ledger = new WelfareLedgerService();
        $sum = $ledger->summaryForMonth($month, $year);

        $paymentsTotal = $sum['payments_total'];
        $incomeTotal = $sum['income_total'];
        $expenseTotal = $sum['expense_total'];
        $totalReceived = $sum['total_received'];
        $totalUsed = $sum['total_used'];
        $closingBalance = $sum['closing_balance'];

        return view('welfear.index', compact(
            'wMonth',
            'transactions',
            'month',
            'year',
            'paymentsTotal',
            'incomeTotal',
            'expenseTotal',
            'totalReceived',
            'totalUsed',
            'closingBalance'
        ));
    }

    public function addIncome(Request $request)
    {
        $auth = $request->user();

        $validator = Validator::make($request->all(), [
            'month'   => 'required|integer|min:1|max:12',
            'year'    => 'required|integer|min:2000|max:2100',
            'amount'  => 'required|numeric|min:1',
            'tx_date' => 'nullable|date',
            'purpose' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $month = (int)$request->month;
        $year  = (int)$request->year;

        $wMonth = $this->getOrCreateMonth($month, $year);

        // ✅ OLD snapshot before create income + month sync
        $oldMonth = $wMonth->fresh()->toArray();

        $tx = WelfareTransaction::create([
            'welfare_month_id' => $wMonth->id,
            'type'             => 'income',
            'amount'           => (float)$request->amount,
            'purpose'          => $request->purpose,
            'tx_date'          => $request->tx_date,
            'created_by'       => $auth->id,
        ]);

        $ledger = new WelfareLedgerService();
        $ledger->syncWelfareMonthRow($wMonth->fresh());

        $paymentsTotal = $ledger->paymentsTotalForCalendarMonth($month, $year);
        $incomeTotal = $ledger->welfareIncomeForCalendarMonth($month, $year);
        $expenseTotal = $ledger->welfareExpenseForCalendarMonth($month, $year);

        // ✅ AUDIT LOG (INCOME CREATED)
        $new = [
            'transaction' => $tx->toArray(),
            'month_after' => $wMonth->fresh()->toArray(),
            'month_before' => $oldMonth,
            'computed' => [
                'paymentsTotal' => $paymentsTotal,
                'incomeTotal' => $incomeTotal,
                'expenseTotal' => $expenseTotal,
            ],
        ];
        $this->audit('created', 'Welfare Income Added', WelfareTransaction::class, $tx->id, null, $new);

        return back()->with('success', 'Amount added to welfare successfully!');
    }

    public function addExpense(Request $request)
    {
        $auth = $request->user();

        $validator = Validator::make($request->all(), [
            'month'      => 'required|integer|min:1|max:12',
            'year'       => 'required|integer|min:2000|max:2100',
            'amount'     => 'required|numeric|min:1',
            'tx_date'    => 'nullable|date',
            'purpose'    => 'required|string|max:255',
            'bill_image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $month = (int)$request->month;
        $year  = (int)$request->year;

        $wMonth = $this->getOrCreateMonth($month, $year);

        // ✅ OLD snapshot before expense
        $oldMonth = $wMonth->fresh()->toArray();

        $ledger = new WelfareLedgerService();
        $sumBefore = $ledger->summaryForMonth($month, $year);
        $paymentsTotal = $sumBefore['payments_total'];
        $incomeTotal = $sumBefore['income_total'];
        $expenseTotal = $sumBefore['expense_total'];
        $available = $sumBefore['closing_balance'];

        if ((float)$request->amount > $available) {
            return back()->with('error', 'Insufficient balance! Remaining: Rs ' . number_format($available, 2));
        }

        $billPath = null;
        if ($request->hasFile('bill_image')) {
            $path = public_path('uploads/bills');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $name = time() . '_bill.' . $request->bill_image->extension();
            $request->bill_image->move($path, $name);
            $billPath = 'uploads/bills/' . $name;
        }

        $tx = WelfareTransaction::create([
            'welfare_month_id' => $wMonth->id,
            'type'             => 'expense',
            'amount'           => (float)$request->amount,
            'purpose'          => $request->purpose,
            'tx_date'          => $request->tx_date,
            'bill_image'       => $billPath,
            'created_by'       => $auth->id,
        ]);

        $ledger->syncWelfareMonthRow($wMonth->fresh());
        $sumAfter = $ledger->summaryForMonth($month, $year);

        // ✅ AUDIT LOG (EXPENSE CREATED)
        $new = [
            'transaction' => $tx->toArray(),
            'month_after' => $wMonth->fresh()->toArray(),
            'month_before' => $oldMonth,
            'computed' => [
                'paymentsTotal' => $sumAfter['payments_total'],
                'incomeTotal' => $sumAfter['income_total'],
                'expenseTotal' => $sumAfter['expense_total'],
            ],
        ];
        $this->audit('created', 'Welfare Expense Added', WelfareTransaction::class, $tx->id, null, $new);

        return back()->with('success', 'Expense saved successfully!');
    }

    private function getOrCreateMonth(int $month, int $year): WelfareMonth
    {
        $ledger = new WelfareLedgerService();

        $record = WelfareMonth::where('month', $month)->where('year', $year)->first();
        if ($record) {
            $ledger->syncWelfareMonthRow($record);

            return $record->fresh();
        }

        $prev = Carbon::createFromDate($year, $month, 1)->subMonth();
        $prevMonth = WelfareMonth::where('month', $prev->month)->where('year', $prev->year)->first();

        $opening = (float) ($prevMonth?->closing_balance ?? 0);

        $newMonth = WelfareMonth::create([
            'month'           => $month,
            'year'            => $year,
            'opening_balance' => $opening,
            'total_received'  => 0,
            'total_used'      => 0,
            'closing_balance' => $opening,
        ]);

        $this->audit('created', 'Welfare Month Created', WelfareMonth::class, $newMonth->id, null, $newMonth->toArray());

        $ledger->syncWelfareMonthRow($newMonth);

        return $newMonth->fresh();
    }

    public function history(Request $request)
    {
        $month = $request->filled('month') ? (int) $request->month : null;
        $year  = $request->filled('year') ? (int) $request->year : now()->year;

        $monthsList = WelfareMonth::query()
            ->when($year, fn($q) => $q->where('year', $year))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $selectedMonth = null;
        if ($month) {
            $selectedMonth = WelfareMonth::where('month', $month)->where('year', $year)->first();
        }

        // ✅ Computed monthly summary (includes payments)
        $computedPaymentsTotal = null;
        $computedIncomeTotal = null;
        $computedExpenseTotal = null;
        $computedTotalReceived = null;
        $computedClosingBalance = null;

        if ($selectedMonth) {
            $ledger = new WelfareLedgerService();
            $histSum = $ledger->summaryForMonth((int) $selectedMonth->month, (int) $selectedMonth->year);
            $computedPaymentsTotal = $histSum['payments_total'];
            $computedIncomeTotal = $histSum['income_total'];
            $computedExpenseTotal = $histSum['expense_total'];
            $computedTotalReceived = $histSum['total_received'];
            $computedClosingBalance = $histSum['closing_balance'];
        }

        $transactions = WelfareTransaction::query()
            ->with(['month', 'creator:id,name'])
            ->when($year, fn($q) => $q->whereHas('month', fn($mq) => $mq->where('year', $year)))
            ->when($month, fn($q) => $q->whereHas('month', fn($mq) => $mq->where('month', $month)))
            ->latest()
            ->get();

        return view('welfear.history', compact(
            'transactions',
            'monthsList',
            'selectedMonth',
            'month',
            'year',
            'computedPaymentsTotal',
            'computedIncomeTotal',
            'computedExpenseTotal',
            'computedTotalReceived',
            'computedClosingBalance'
        ));
    }
}
