<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Damage;
use App\Models\DeliveryOrder;
use App\Models\GalleryImage;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Payment;
use App\Models\WelfareMonth;
use App\Models\WelfareTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    private function isAdmin($user): bool
    {
        return strtolower((string) ($user->role ?? '')) === 'admin';
    }

    private function monthYear(Request $request): array
    {
        $now = Carbon::now();
        $month = (int) $request->query('month', $now->month);
        $year = (int) $request->query('year', $now->year);

        if ($month < 1 || $month > 12) {
            $month = (int) $now->month;
        }
        if ($year < 2024 || $year > 2100) {
            $year = (int) $now->year;
        }

        return [$month, $year];
    }

    public function index(Request $request)
    {
        $auth = $request->user();
        abort_unless($auth, 403);

        [$month, $year] = $this->monthYear($request);
        $payload = $this->buildPayload($auth, $month, $year);

        return view('reports.monthly', [
            'month' => $month,
            'year' => $year,
            'isAdmin' => $this->isAdmin($auth),
            'reportPayload' => $payload,
            'yearOptions' => range((int) now()->year + 1, 2024),
        ]);
    }

    public function data(Request $request)
    {
        $auth = $request->user();
        abort_unless($auth, 403);

        [$month, $year] = $this->monthYear($request);
        return response()->json($this->buildPayload($auth, $month, $year));
    }

    private function buildPayload($auth, int $month, int $year): array
    {
        $isAdmin = $this->isAdmin($auth);

        $payments = Payment::query()->whereYear('date', $year)->whereMonth('date', $month);
        if (!$isAdmin) {
            $payments->where('user_id', $auth->id);
        }

        $welfareMonths = WelfareMonth::query()->where('year', $year)->where('month', $month);
        $welfareMonthIds = (clone $welfareMonths)->pluck('id');
        $welfareTx = WelfareTransaction::query()->whereIn('welfare_month_id', $welfareMonthIds);
        if (!$isAdmin) {
            $welfareTx->where('created_by', $auth->id);
        }

        $orders = Order::query()->whereYear('created_at', $year)->whereMonth('created_at', $month);
        if (!$isAdmin) {
            $orders->where('user_id', $auth->id);
        }

        $deliveries = DeliveryOrder::query()->whereYear('delivery_date', $year)->whereMonth('delivery_date', $month);
        if (!$isAdmin) {
            $deliveries->where('user_id', $auth->id);
        }

        $damages = Damage::query()->where(function ($q) use ($month, $year) {
            $q->where(function ($d) use ($month, $year) {
                $d->whereNotNull('damage_date')->whereYear('damage_date', $year)->whereMonth('damage_date', $month);
            })->orWhere(function ($d) use ($month, $year) {
                $d->whereNull('damage_date')->whereYear('created_at', $year)->whereMonth('created_at', $month);
            });
        });
        if (!$isAdmin) {
            $damages->where('user_id', $auth->id);
        }

        $complaints = Complaint::query()->whereYear('created_at', $year)->whereMonth('created_at', $month);
        if (!$isAdmin) {
            $complaints->where('user_id', $auth->id);
        }

        $gallery = GalleryImage::query()->whereYear('created_at', $year)->whereMonth('created_at', $month);
        if (!$isAdmin) {
            $gallery->where('uploaded_by', $auth->id);
        }

        $leads = Lead::query()->whereYear('created_at', $year)->whereMonth('created_at', $month);
        if (!$isAdmin) {
            $leads->where(function ($q) use ($auth) {
                $q->where('email', $auth->email ?? '')
                    ->orWhere('id_card', $auth->id_card ?? '');
            });
        }

        $kpis = [
            'payments_count' => (clone $payments)->count(),
            'payments_amount' => (float) ((clone $payments)->sum('amount') ?: 0),
            'welfare_income' => (float) ((clone $welfareTx)->where('type', 'income')->sum('amount') ?: 0),
            'welfare_expense' => (float) ((clone $welfareTx)->where('type', 'expense')->sum('amount') ?: 0),
            'orders_count' => (clone $orders)->count(),
            'deliveries_count' => (clone $deliveries)->count(),
            'damages_count' => (clone $damages)->count(),
            'damages_fine' => (float) ((clone $damages)->sum('fine') ?: 0),
            'complaints_count' => (clone $complaints)->count(),
            'gallery_uploads' => (clone $gallery)->count(),
            'leads_count' => (clone $leads)->count(),
        ];
        $kpis['welfare_net'] = $kpis['welfare_income'] - $kpis['welfare_expense'];

        $orderStatuses = ['pending', 'confirmed', 'delivered', 'cancelled'];
        $orderStatusMap = (clone $orders)->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
        $ordersByStatus = [];
        foreach ($orderStatuses as $st) {
            $ordersByStatus[$st] = (int) ($orderStatusMap[$st] ?? 0);
        }

        $complaintTypeMap = (clone $complaints)->selectRaw('type, COUNT(*) as total')->groupBy('type')->pluck('total', 'type');
        $leadStatusMap = (clone $leads)->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
        $damageTotalQty = (int) ((clone $damages)->sum('qty') ?: 0);

        $trend = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'paymentsAmount' => [],
            'ordersCount' => [],
            'deliveriesCount' => [],
            'complaintsCount' => [],
            'galleryUploads' => [],
            'leadsCount' => [],
        ];

        for ($m = 1; $m <= 12; $m++) {
            $pQ = Payment::query()->whereYear('date', $year)->whereMonth('date', $m);
            $oQ = Order::query()->whereYear('created_at', $year)->whereMonth('created_at', $m);
            $dQ = DeliveryOrder::query()->whereYear('delivery_date', $year)->whereMonth('delivery_date', $m);
            $cQ = Complaint::query()->whereYear('created_at', $year)->whereMonth('created_at', $m);
            $gQ = GalleryImage::query()->whereYear('created_at', $year)->whereMonth('created_at', $m);
            $lQ = Lead::query()->whereYear('created_at', $year)->whereMonth('created_at', $m);

            if (!$isAdmin) {
                $pQ->where('user_id', $auth->id);
                $oQ->where('user_id', $auth->id);
                $dQ->where('user_id', $auth->id);
                $cQ->where('user_id', $auth->id);
                $gQ->where('uploaded_by', $auth->id);
                $lQ->where(function ($q) use ($auth) {
                    $q->where('email', $auth->email ?? '')
                        ->orWhere('id_card', $auth->id_card ?? '');
                });
            }

            $trend['paymentsAmount'][] = (float) ($pQ->sum('amount') ?: 0);
            $trend['ordersCount'][] = (int) $oQ->count();
            $trend['deliveriesCount'][] = (int) $dQ->count();
            $trend['complaintsCount'][] = (int) $cQ->count();
            $trend['galleryUploads'][] = (int) $gQ->count();
            $trend['leadsCount'][] = (int) $lQ->count();
        }

        $moduleContribution = [
            'labels' => ['Payments', 'Orders', 'Deliveries', 'Damages', 'Complaints', 'Gallery', 'Leads'],
            'values' => [
                (int) $kpis['payments_count'],
                (int) $kpis['orders_count'],
                (int) $kpis['deliveries_count'],
                (int) $kpis['damages_count'],
                (int) $kpis['complaints_count'],
                (int) $kpis['gallery_uploads'],
                (int) $kpis['leads_count'],
            ],
        ];

        $tables = [
            'payments' => (clone $payments)->with('user:id,name')->latest('date')->limit(10)->get(['id', 'user_id', 'amount', 'month', 'date']),
            'orders' => (clone $orders)->with('user:id,name')->latest()->limit(10)->get(['id', 'user_id', 'status', 'created_at']),
            'deliveries' => (clone $deliveries)->with('user:id,name')->latest()->limit(10)->get(['id', 'user_id', 'delivery_date', 'delivery_time', 'created_at']),
            'damages' => (clone $damages)->with('user:id,name')->latest()->limit(10)->get(['id', 'user_id', 'qty', 'fine', 'damage_date', 'created_at']),
            'complaints' => (clone $complaints)->with('user:id,name')->latest()->limit(10)->get(['id', 'user_id', 'type', 'status', 'subject', 'created_at']),
            'gallery' => (clone $gallery)->with('user:id,name')->latest()->limit(10)->get(['id', 'uploaded_by', 'title', 'created_at']),
            'leads' => (clone $leads)->latest()->limit(10)->get(['id', 'name', 'phone', 'status', 'created_at']),
        ];

        return [
            'month' => $month,
            'year' => $year,
            'is_admin' => $isAdmin,
            'kpis' => $kpis,
            'breakdowns' => [
                'orders_status' => $ordersByStatus,
                'deliveries_status' => [],
                'complaints_type' => [
                    'complaint' => (int) ($complaintTypeMap['complaint'] ?? 0),
                    'suggestion' => (int) ($complaintTypeMap['suggestion'] ?? 0),
                ],
                'leads_status' => [
                    'new' => (int) ($leadStatusMap['new'] ?? 0),
                    'contacted' => (int) ($leadStatusMap['contacted'] ?? 0),
                    'approved' => (int) ($leadStatusMap['approved'] ?? 0),
                    'rejected' => (int) ($leadStatusMap['rejected'] ?? 0),
                ],
                'damages_qty' => $damageTotalQty,
            ],
            'trend' => $trend,
            'moduleContribution' => $moduleContribution,
            'tables' => $tables,
        ];
    }
}

