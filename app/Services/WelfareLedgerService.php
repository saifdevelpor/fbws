<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\WelfareMonth;
use App\Models\WelfareTransaction;
use Carbon\Carbon;

/**
 * Single source of truth for welfare math:
 * - Payments count by payment `date` calendar month (same as WelfareFundController).
 * - Opening for month M = closing of M-1, chained from earliest activity.
 * - First WelfareMonth row can define a floor opening on its month via max(chain, DB opening).
 */
class WelfareLedgerService
{
    public function paymentsTotalForCalendarMonth(int $month, int $year): float
    {
        return (float) Payment::query()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');
    }

    public function welfareIncomeForCalendarMonth(int $month, int $year): float
    {
        $wm = WelfareMonth::where('month', $month)->where('year', $year)->first();
        if (!$wm) {
            return 0.0;
        }

        return (float) WelfareTransaction::query()
            ->where('welfare_month_id', $wm->id)
            ->where('type', 'income')
            ->sum('amount');
    }

    public function welfareExpenseForCalendarMonth(int $month, int $year): float
    {
        $wm = WelfareMonth::where('month', $month)->where('year', $year)->first();
        if (!$wm) {
            return 0.0;
        }

        return (float) WelfareTransaction::query()
            ->where('welfare_month_id', $wm->id)
            ->where('type', 'expense')
            ->sum('amount');
    }

    /**
     * @return array{
     *     opening_balance: float,
     *     payments_total: float,
     *     income_total: float,
     *     expense_total: float,
     *     total_received: float,
     *     total_used: float,
     *     closing_balance: float
     * }
     */
    public function summaryForMonth(int $month, int $year): array
    {
        $opening = $this->openingAtStartOfMonth(Carbon::createFromDate($year, $month, 1)->startOfMonth());
        $payments = $this->paymentsTotalForCalendarMonth($month, $year);
        $income = $this->welfareIncomeForCalendarMonth($month, $year);
        $expense = $this->welfareExpenseForCalendarMonth($month, $year);
        $totalReceived = $payments + $income;
        $totalUsed = $expense;
        $closing = $opening + $totalReceived - $totalUsed;

        return [
            'opening_balance' => $opening,
            'payments_total' => $payments,
            'income_total' => $income,
            'expense_total' => $expense,
            'total_received' => $totalReceived,
            'total_used' => $totalUsed,
            'closing_balance' => $closing,
        ];
    }

    public function openingBalanceForMonth(int $month, int $year): float
    {
        return $this->openingAtStartOfMonth(Carbon::createFromDate($year, $month, 1)->startOfMonth());
    }

    public function closingBalanceForMonth(int $month, int $year): float
    {
        return $this->summaryForMonth($month, $year)['closing_balance'];
    }

    /**
     * Align stored welfare_month row with computed ledger (fixes stale opening/next month).
     */
    public function syncWelfareMonthRow(WelfareMonth $wm): void
    {
        $s = $this->summaryForMonth((int) $wm->month, (int) $wm->year);
        $wm->opening_balance = $s['opening_balance'];
        $wm->total_received = $s['total_received'];
        $wm->total_used = $s['total_used'];
        $wm->closing_balance = $s['closing_balance'];
        $wm->save();
    }

    protected function openingAtStartOfMonth(Carbon $targetMonthStart): float
    {
        $target = $targetMonthStart->copy()->startOfMonth();

        $globalStart = $this->globalLedgerStart();
        if (!$globalStart) {
            return 0.0;
        }

        if ($target->lt($globalStart)) {
            return 0.0;
        }

        $firstWM = WelfareMonth::query()->orderBy('year')->orderBy('month')->first();
        $firstWMStart = $firstWM
            ? Carbon::createFromDate((int) $firstWM->year, (int) $firstWM->month, 1)->startOfMonth()
            : null;

        $runner = 0.0;
        $cursor = $globalStart->copy();

        while ($cursor->lt($target)) {
            if ($firstWMStart && $cursor->equalTo($firstWMStart)) {
                $runner = max($runner, (float) $firstWM->opening_balance);
            }
            $m = (int) $cursor->month;
            $y = (int) $cursor->year;
            $runner += $this->paymentsTotalForCalendarMonth($m, $y)
                + $this->welfareIncomeForCalendarMonth($m, $y)
                - $this->welfareExpenseForCalendarMonth($m, $y);
            $cursor->addMonth();
        }

        if ($firstWMStart && $target->equalTo($firstWMStart)) {
            $runner = max($runner, (float) $firstWM->opening_balance);
        }

        return $runner;
    }

    protected function globalLedgerStart(): ?Carbon
    {
        $firstPayDate = Payment::query()->min('date');
        $firstWM = WelfareMonth::query()->orderBy('year')->orderBy('month')->first();

        $candidates = [];
        if ($firstPayDate) {
            $candidates[] = Carbon::parse($firstPayDate)->startOfMonth();
        }
        if ($firstWM) {
            $candidates[] = Carbon::createFromDate((int) $firstWM->year, (int) $firstWM->month, 1)->startOfMonth();
        }

        if ($candidates === []) {
            return null;
        }

        $min = $candidates[0];
        foreach ($candidates as $c) {
            if ($c->lt($min)) {
                $min = $c;
            }
        }

        return $min->copy();
    }
}
