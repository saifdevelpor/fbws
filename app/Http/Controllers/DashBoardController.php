<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payment;
use App\Models\SupportRequest;
use App\Models\WelfareMonth;
use App\Models\WelfareTransaction;
use App\Models\DeliveryOrder;
use App\Models\LoginHistory;
use App\Models\Order;
use App\Models\Complaint;
use App\Models\Damage;
use App\Models\Lead;
use App\Models\Event;
use App\Models\GalleryImage;
use App\Services\WelfareLedgerService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $auth = $request->user();
        $isAdmin = strtolower(trim((string) ($auth->role ?? ''))) === 'admin';

        $displayTimezone = config('app.timezone');
        $now = Carbon::now($displayTimezone);
        $monthNum  = (int) $now->month;
        $yearNum   = (int) $now->year;
        $monthName = $now->format('F');

        // ✅ Current month datetime range (for modules keyed by created_at)
        $startDT   = $now->copy()->startOfMonth()->startOfDay();
        $endDT     = $now->copy()->endOfMonth()->endOfDay();

        // ==========================
        // ✅ Login History Metrics
        // ==========================
        $last6StartDT = $now->copy()->subMonths(5)->startOfMonth()->startOfDay();
        $last6EndDT   = $now->copy()->endOfDay();

        if ($isAdmin) {
            // Admin: how many users logged in (unique users)
            $loginThisMonthCount = (int) LoginHistory::query()
                ->whereBetween('logged_in_at', [$startDT, $endDT])
                ->distinct('user_id')
                ->count('user_id');

            $loginLast6Count = (int) LoginHistory::query()
                ->whereBetween('logged_in_at', [$last6StartDT, $last6EndDT])
                ->distinct('user_id')
                ->count('user_id');

            $loginThisMonthRows = LoginHistory::query()
                ->with(['user:id,name,id_card,profile_photo'])
                ->whereBetween('logged_in_at', [$startDT, $endDT])
                ->latest('logged_in_at')
                ->take(500)
                ->get();

            $loginLast6Rows = LoginHistory::query()
                ->with(['user:id,name,id_card,profile_photo'])
                ->whereBetween('logged_in_at', [$last6StartDT, $last6EndDT])
                ->latest('logged_in_at')
                ->take(800)
                ->get();

            $loginThisMonthRows = $loginThisMonthRows
                ->groupBy('user_id')
                ->map(function ($rows) {
                    $latest = $rows->sortByDesc('logged_in_at')->first();
                    $latest->login_count = $rows->count();
                    return $latest;
                })
                ->sortByDesc('logged_in_at')
                ->values();

            $loginLast6Rows = $loginLast6Rows
                ->groupBy('user_id')
                ->map(function ($rows) {
                    $latest = $rows->sortByDesc('logged_in_at')->first();
                    $latest->login_count = $rows->count();
                    return $latest;
                })
                ->sortByDesc('logged_in_at')
                ->values();
        } else {
            // User: how many times I logged in
            $loginThisMonthCount = (int) LoginHistory::query()
                ->where('user_id', $auth->id)
                ->whereBetween('logged_in_at', [$startDT, $endDT])
                ->count();

            $loginLast6Count = (int) LoginHistory::query()
                ->where('user_id', $auth->id)
                ->whereBetween('logged_in_at', [$last6StartDT, $last6EndDT])
                ->count();

            $loginThisMonthRows = LoginHistory::query()
                ->where('user_id', $auth->id)
                ->whereBetween('logged_in_at', [$startDT, $endDT])
                ->latest('logged_in_at')
                ->take(500)
                ->get();

            $loginLast6Rows = LoginHistory::query()
                ->where('user_id', $auth->id)
                ->whereBetween('logged_in_at', [$last6StartDT, $last6EndDT])
                ->latest('logged_in_at')
                ->take(800)
                ->get();
        }

        // Force display time in configured application timezone for dashboard login modals
        $mapLoginRow = function ($row) {
            $karachi = $row->logged_in_at
                ? Carbon::parse($row->logged_in_at)->setTimezone(config('app.timezone'))
                : null;
            $row->karachi_date = $karachi ? $karachi->format('d M Y') : '—';
            $row->karachi_time = $karachi ? $karachi->format('h:i A') : '—';
            return $row;
        };
        $loginThisMonthRows = $loginThisMonthRows->map($mapLoginRow);
        $loginLast6Rows = $loginLast6Rows->map($mapLoginRow);

        // Monthly login chart (Last 12 months)
        $loginChartLabels = [];
        $loginChartData = [];

        for ($i = 11; $i >= 0; $i--) {
            $d = $now->copy()->subMonths($i);
            $loginChartLabels[] = $d->format('M Y');
            $mStart = $d->copy()->startOfMonth()->startOfDay();
            $mEnd = $d->copy()->endOfMonth()->endOfDay();

            if ($isAdmin) {
                $loginChartData[] = (int) LoginHistory::query()
                    ->whereBetween('logged_in_at', [$mStart, $mEnd])
                    ->distinct('user_id')
                    ->count('user_id');
            } else {
                $loginChartData[] = (int) LoginHistory::query()
                    ->where('user_id', $auth->id)
                    ->whereBetween('logged_in_at', [$mStart, $mEnd])
                    ->count();
            }
        }

        $loginChartDatasetLabel = $isAdmin ? 'Users Logged In' : 'My Logins';

        // ==========================
        // ✅ Current Month Welfare (aligned with Welfare Fund: calendar month + chained opening)
        // ==========================
        $wMonthModel = WelfareMonth::where('month', $monthNum)->where('year', $yearNum)->first();

        $ledger = new WelfareLedgerService();
        $wSum = $ledger->summaryForMonth($monthNum, $yearNum);

        $opening = $wSum['opening_balance'];
        $paymentsTotal = $wSum['payments_total'];
        $incomeTotal = $wSum['income_total'];
        $expenseTotal = $wSum['expense_total'];
        $totalReceived = $wSum['total_received'];
        $totalUsed = $wSum['total_used'];
        $closingBalance = $wSum['closing_balance'];

        $wMonth = (object) [
            'opening_balance' => $opening,
            'total_received'  => $totalReceived,
            'total_used'      => $totalUsed,
            'closing_balance' => $closingBalance,
        ];

        // ==========================
        // ✅ Admin Metrics
        // ==========================
        $memberBaseQuery = User::query()->where('position', '!=', 'Legal Advisor');

        $totalUsers   = (clone $memberBaseQuery)->count();
        $totalMembers = (clone $memberBaseQuery)->count();

        $paidMemberIds = Payment::query()
            ->whereYear('date', $yearNum)
            ->whereMonth('date', $monthNum)
            ->whereIn('user_id', (clone $memberBaseQuery)->pluck('id'))
            ->distinct()
            ->pluck('user_id');

        $paidCount = $paidMemberIds->count();
        $unpaidCount = max(0, (int) $totalMembers - (int) $paidCount);

        // ==========================
        // ✅ Current Month Lists for MODALS
        // ==========================

        // 1) Current month payments list
        $cmPaymentsQ = Payment::query()->with(['user:id,name,profile_photo']);

        if (!$isAdmin) {
            $cmPaymentsQ->where('user_id', $auth->id);
        }

        $cmPayments = $cmPaymentsQ
            ->whereYear('date', $yearNum)
            ->whereMonth('date', $monthNum)
            ->latest('date')
            ->latest('time')
            ->latest('created_at')
            ->get();

        // 2) Current month expenses list ✅ FIX (profile_photo + correct FK creator_id)
        $cmExpensesQ = WelfareTransaction::query()
            ->with(['creator:id,name,profile_photo', 'month'])
            ->where('type', 'expense');

        if (!$isAdmin) {
            $cmExpensesQ->where('created_by', $auth->id); // ✅ FIX (was created_by)
        }

        if ($wMonthModel) {
            $cmExpensesQ->where('welfare_month_id', $wMonthModel->id);
        } else {
            $cmExpensesQ->whereBetween('created_at', [$startDT, $endDT]);
        }

        $cmExpenses = $cmExpensesQ->latest()->get();

        // 3) Current month complaints list ✅ FIX (profile_photo)
        $cmComplaintsQ = Complaint::query()
            ->with(['user:id,name,profile_photo'])
            ->whereBetween('created_at', [$startDT, $endDT])
            ->where('type', 'complaint');

        if (!$isAdmin) {
            $cmComplaintsQ->where('user_id', $auth->id);
        }

        $cmComplaints = $cmComplaintsQ->latest()->get();

        // 4) Current month suggestions list ✅ FIX (profile_photo)
        $cmSuggestionsQ = Complaint::query()
            ->with(['user:id,name,profile_photo'])
            ->whereBetween('created_at', [$startDT, $endDT])
            ->where('type', 'suggestion');

        if (!$isAdmin) {
            $cmSuggestionsQ->where('user_id', $auth->id);
        }

        $cmSuggestions = $cmSuggestionsQ->latest()->get();

        // 5) Current month damages list ✅ FIX (profile_photo)
        $cmDamagesQ = Damage::query()
            ->with(['user:id,name,profile_photo'])
            ->whereBetween('created_at', [$startDT, $endDT]);

        if (!$isAdmin) {
            $cmDamagesQ->where('user_id', $auth->id);
        }

        $cmDamages = $cmDamagesQ->latest()->get();

        // ==========================
        // ✅ Last 5 Welfare Transactions ✅ FIX (profile_photo + correct FK)
        // ==========================
        $txQuery = WelfareTransaction::with(['creator:id,name,profile_photo', 'month'])->latest();

        if (!$isAdmin) {
            $txQuery->where('created_by', $auth->id); // ✅ FIX (was created_by)
        }

        $lastFiveTx = $txQuery->take(5)->get();

        // ==========================
        // ✅ User Metrics
        // ==========================
        $userThisMonthContribution = null;
        $userTotalContribution = null;
        $userLastPayment = null;

        if (!$isAdmin) {
            $userThisMonthContribution = (float) Payment::where('user_id', $auth->id)
                ->whereYear('date', $yearNum)
                ->whereMonth('date', $monthNum)
                ->sum('amount');

            $userTotalContribution = (float) Payment::where('user_id', $auth->id)->sum('amount');

            $userLastPayment = Payment::where('user_id', $auth->id)
                ->latest('date')->latest('time')->latest('created_at')
                ->first();
        } else {
            $userLastPayment = Payment::latest('date')->latest('time')->latest('created_at')->first();
        }

        // ==========================
        // ✅ Chart Data (Last 6 months)
        // ==========================
        $labels = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            $d = $now->copy()->subMonths($i);
            $m = (int) $d->month;
            $y = (int) $d->year;

            $labels[] = $d->format('M Y');

            $paySum = $ledger->paymentsTotalForCalendarMonth($m, $y);

            $wm = WelfareMonth::where('month', $m)->where('year', $y)->first();

            $incSum = 0.0;
            $expSum = 0.0;

            if ($wm) {
                $incSum = (float) WelfareTransaction::where('welfare_month_id', $wm->id)->where('type', 'income')->sum('amount');
                $expSum = (float) WelfareTransaction::where('welfare_month_id', $wm->id)->where('type', 'expense')->sum('amount');
            }

            $incomeData[]  = (float) ($paySum + $incSum);
            $expenseData[] = (float) $expSum;
        }

        // ==========================
        // ✅ Last Payments table
        // ==========================
        $paymentQuery = Payment::query()
            ->with(['user:id,name,profile_photo'])
            ->latest('date')
            ->latest('time')
            ->latest('created_at');

        if ($isAdmin) {
            // ✅ Admin: only current month latest 5 (calendar month of payment date)
            $paymentQuery
                ->whereYear('date', $yearNum)
                ->whereMonth('date', $monthNum);
        } else {
            // ✅ User: only own latest 5 (all time)
            $paymentQuery->where('user_id', $auth->id);
        }

        $lastPayments = $paymentQuery->take(3)->get();

        // ==========================
        // ✅ Orders
        // ==========================
        if ($isAdmin) {
            $lastOrders = DeliveryOrder::with(['user', 'items.item'])
                ->latest()
                ->take(5)
                ->get();
        } else {
            $lastOrders = DeliveryOrder::with(['user', 'items.item'])
                ->where(function ($q) use ($auth) {
                    $q->where('user_id', $auth->id)
                      ->orWhere('created_by', $auth->id);
                })
                ->latest()
                ->take(5)
                ->get();
        }

        // ==========================
        // ✅ Complaints & Suggestions Counts (for charts/cards)
        // ==========================
        $complaintCounts = [];
        $suggestionCounts = [];

        for ($i = 5; $i >= 0; $i--) {
            $d = $now->copy()->subMonths($i);
            $m = (int) $d->month;
            $y = (int) $d->year;

            $q = Complaint::query()
                ->whereYear('created_at', $y)
                ->whereMonth('created_at', $m);

            if (!$isAdmin) {
                $q->where('user_id', $auth->id);
            }

            $complaintCounts[]  = (int) (clone $q)->where('type', 'complaint')->count();
            $suggestionCounts[] = (int) (clone $q)->where('type', 'suggestion')->count();
        }

        $cmQ = Complaint::query()
            ->whereYear('created_at', $yearNum)
            ->whereMonth('created_at', $monthNum);

        if (!$isAdmin) {
            $cmQ->where('user_id', $auth->id);
        }

        $currentMonthComplaints  = (int) (clone $cmQ)->where('type', 'complaint')->count();
        $currentMonthSuggestions = (int) (clone $cmQ)->where('type', 'suggestion')->count();

        $baseAll = $isAdmin ? Complaint::query() : Complaint::where('user_id', $auth->id);

        $totalComplaintsAllTime  = (int) (clone $baseAll)->where('type', 'complaint')->count();
        $totalSuggestionsAllTime = (int) (clone $baseAll)->where('type', 'suggestion')->count();

        // ==========================
        // ✅ Damages chart + cards
        // ==========================
        $damageCounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = $now->copy()->subMonths($i);
            $m = (int) $d->month;
            $y = (int) $d->year;

            $dq = Damage::query()
                ->whereYear('created_at', $y)
                ->whereMonth('created_at', $m);

            if (!$isAdmin) {
                $dq->where('user_id', $auth->id);
            }

            $damageCounts[] = (int) $dq->count();
        }

        $cmdQ = Damage::query()
            ->whereYear('created_at', $yearNum)
            ->whereMonth('created_at', $monthNum);

        if (!$isAdmin) {
            $cmdQ->where('user_id', $auth->id);
        }

        $currentMonthDamages = (int) $cmdQ->count();

        $totalDamagesAllTime = $isAdmin
            ? (int) Damage::count()
            : (int) Damage::where('user_id', $auth->id)->count();

        // ==========================
        // ✅ SPARK DATA (DYNAMIC)
        // ==========================
        $payBase = Payment::query();
        if (!$isAdmin) {
            $payBase->where('user_id', $auth->id);
        }

        /**
         * Spark 1: Last 7 Days payments (daily totals)
         */
        $spark1Labels = [];
        $spark1Data = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i);
            $d1 = $day->copy()->startOfDay();
            $d2 = $day->copy()->endOfDay();

            $spark1Labels[] = $day->format('d M');

            $sum = (clone $payBase)
                ->whereBetween('date', [$d1->toDateString(), $d2->toDateString()])
                ->sum('amount');

            $spark1Data[] = (float) $sum;
        }

        /**
         * Spark 2: Current Month daily payments
         */
        $spark2Labels = [];
        $spark2Data = [];

        $daysInMonth = $now->daysInMonth;
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $day = Carbon::create($yearNum, $monthNum, $d, 0, 0, 0, $displayTimezone);
            $d1 = $day->copy()->startOfDay();
            $d2 = $day->copy()->endOfDay();

            $spark2Labels[] = $day->format('d');

            $sum = (clone $payBase)
                ->whereDate('date', $day->toDateString())
                ->sum('amount');

            $spark2Data[] = (float) $sum;
        }

        /**
         * Spark 3: Last 12 months payments (monthly totals)
         */
        $spark3Labels = [];
        $spark3Data = [];

        for ($i = 11; $i >= 0; $i--) {
            $mObj = $now->copy()->subMonths($i);

            $spark3Labels[] = $mObj->format('M Y');

            $sum = (clone $payBase)
                ->whereYear('date', (int) $mObj->year)
                ->whereMonth('date', (int) $mObj->month)
                ->sum('amount');

            $spark3Data[] = (float) $sum;
        }

        // ==========================
        // ✅ Admin Users Lists for MODALS
        // ==========================
        $allUsers = collect();
        $paidUsers = collect();
        $unpaidUsers = collect();


        $allUsers = User::query()
            ->select('id', 'name', 'email', 'role', 'profile_photo')
            ->where('position', '!=', 'Legal Advisor')
            ->latest()
            ->get();

        $paidUsers = Payment::query()
            ->with(['user:id,name,email,profile_photo'])
            ->whereYear('date', $yearNum)
            ->whereMonth('date', $monthNum)
            // ✅ 1 row per user (latest payment in current month)
            ->orderBy('user_id')
            ->latest('date')
            ->latest('time')
            ->latest('created_at')
            ->get()
            ->unique('user_id')
            ->values();

        $unpaidUsers = User::query()
            ->select('id', 'name', 'email', 'profile_photo')
            ->where('position', '!=', 'Legal Advisor')
            ->whereNotIn('id', $paidMemberIds)
            ->orderBy('name')
            ->get();

        // Total Orders (All Time)
        $totalOrders = DeliveryOrder::count();

        // This Month Orders (same calendar month as dashboard $now)
        $thisMonthOrders = DeliveryOrder::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        if (auth()->user()->role === 'admin') {
            $deliveryOrders = DeliveryOrder::with(['user', 'creator'])
                ->latest()
                ->get();
        } else {
            $deliveryOrders = DeliveryOrder::with(['user', 'creator'])
                ->where('user_id', auth()->id())
                ->latest()
                ->get();
        }

        $currentMonthOrders = DeliveryOrder::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->latest()
            ->get();

        $currentMonthCount = $currentMonthOrders->count();
        $pendingOrderRequests = Order::where('is_seen_admin', false)->count();

        // ==========================
        // ✅ Order Requests (new module)
        // ==========================
        $orderBase = Order::query();
        if (!$isAdmin) {
            $orderBase->where('user_id', $auth->id);
        }

        $latestOrderRequests = (clone $orderBase)
            ->with(['user:id,name,profile_photo', 'items.item:id,name,image'])
            ->latest()
            ->take(5)
            ->get();

        $totalOrderRequests = (int) (clone $orderBase)->count();

        $thisMonthOrderRequests = (int) (clone $orderBase)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $allOrderRequests = (clone $orderBase)
            ->with(['user:id,name,profile_photo', 'creator:id,name,profile_photo', 'items.item:id,name,image'])
            ->latest()
            ->get();

        $currentMonthOrderRequests = (clone $orderBase)
            ->with(['user:id,name,profile_photo', 'creator:id,name,profile_photo', 'items.item:id,name,image'])
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->latest()
            ->get();

        $orderRequestLabels = [];
        $orderRequestCounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = $now->copy()->subMonths($i);
            $q = Order::query()
                ->whereYear('created_at', (int) $d->year)
                ->whereMonth('created_at', (int) $d->month);

            if (!$isAdmin) {
                $q->where('user_id', $auth->id);
            }

            $orderRequestLabels[] = $d->format('M Y');
            $orderRequestCounts[] = (int) $q->count();
        }

        $leadCounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = $now->copy()->subMonths($i);

            $leadCounts[] = (int) Lead::query()
                ->whereYear('created_at', (int) $d->year)
                ->whereMonth('created_at', (int) $d->month)
                ->count();
        }

        $eventCounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = $now->copy()->subMonths($i);

            $eventCounts[] = (int) Event::query()
                ->whereYear('created_at', (int) $d->year)
                ->whereMonth('created_at', (int) $d->month)
                ->count();
        }

        $galleryCounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = $now->copy()->subMonths($i);

            $gq = GalleryImage::query()
                ->whereYear('created_at', (int) $d->year)
                ->whereMonth('created_at', (int) $d->month);

            if (!$isAdmin) {
                $gq->where('uploaded_by', $auth->id);
            }

            $galleryCounts[] = (int) $gq->count();
        }

        $openComplaintBase = Complaint::query()
            ->where('type', 'complaint')
            ->where(function ($q) {
                $q->whereNull('status')
                    ->orWhereIn('status', ['new', 'in_progress']);
            });

        if (!$isAdmin) {
            $openComplaintBase->where('user_id', $auth->id);
        }

        $openComplaintCount = (int) (clone $openComplaintBase)->count();

        $supportRequestBase = SupportRequest::query();
        if (!$isAdmin) {
            $supportRequestBase->where('user_id', $auth->id);
        }

        $openSupportRequestCount = (int) (clone $supportRequestBase)
            ->whereIn('status', ['new', 'under_review', 'approved'])
            ->count();

        $newSupportRequestCount = $isAdmin
            ? (int) SupportRequest::query()->where('is_seen_admin', false)->count()
            : 0;

        $newLeadCount = $isAdmin
            ? (int) Lead::query()->where('status', 'new')->count()
            : 0;

        $activeOrderRequestCount = (int) (clone $orderBase)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        $userDeviceCount = (int) LoginHistory::query()
            ->where('user_id', $auth->id)
            ->get()
            ->unique(function ($row) {
                return md5(($row->ip_address ?? 'na') . '|' . ($row->user_agent ?? 'na'));
            })
            ->count();

        $userPaymentStatus = $isAdmin
            ? null
            : ((float) ($userThisMonthContribution ?? 0) > 0 ? 'Paid this month' : 'Payment due this month');

        $userLastPaymentLabel = null;
        if (!$isAdmin && $userLastPayment && $userLastPayment->date) {
            $userLastPaymentLabel = Carbon::parse($userLastPayment->date, config('app.timezone'))
                ->format('d M Y');
        }


        return view('dashboard.list', compact(
            'isAdmin',
            'now',
            'monthName',
            'monthNum',
            'yearNum',

            'wMonth',
            'paymentsTotal',
            'incomeTotal',
            'expenseTotal',

            'totalUsers',
            'totalMembers',
            'paidCount',
            'unpaidCount',

            'lastFiveTx',
            'lastPayments',
            'lastOrders',

            'userThisMonthContribution',
            'userTotalContribution',
            'userLastPayment',

            'labels',
            'incomeData',
            'expenseData',

            'complaintCounts',
            'suggestionCounts',
            'currentMonthComplaints',
            'currentMonthSuggestions',
            'totalComplaintsAllTime',
            'totalSuggestionsAllTime',

            'damageCounts',
            'currentMonthDamages',
            'totalDamagesAllTime',

            // ✅ modal lists
            'cmPayments',
            'cmExpenses',
            'cmComplaints',
            'cmSuggestions',
            'cmDamages',

            'spark1Labels',
            'spark1Data',
            'spark2Labels',
            'spark2Data',
            'spark3Labels',
            'spark3Data',

            'allUsers',
            'paidUsers',
            'unpaidUsers',

            'totalOrders',
            'thisMonthOrders',
            'deliveryOrders',
            'currentMonthOrders',
            'currentMonthCount',
            'monthName',
            'pendingOrderRequests'
            ,
            'latestOrderRequests',
            'totalOrderRequests',
            'thisMonthOrderRequests',
            'allOrderRequests',
            'currentMonthOrderRequests',
            'orderRequestLabels',
            'orderRequestCounts',
            'leadCounts',
            'eventCounts',
            'galleryCounts',
            'openComplaintCount',
            'openSupportRequestCount',
            'newSupportRequestCount',
            'newLeadCount',
            'activeOrderRequestCount',
            'userDeviceCount',
            'userPaymentStatus',
            'userLastPaymentLabel',

            // ✅ Login history cards + modals + chart
            'loginThisMonthCount',
            'loginLast6Count',
            'loginThisMonthRows',
            'loginLast6Rows',
            'loginChartLabels',
            'loginChartData',
            'loginChartDatasetLabel'
        ));
    }
}

/**
 * Helper: check column exists (safe)
 */
function schema_has_column($table, $column)
{
    try {
        return Schema::hasColumn($table, $column);
    } catch (\Throwable $e) {
        return false;
    }
}
