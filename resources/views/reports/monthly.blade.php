@extends('home')

@section('title')
    <title>Monthly Reports & Analytics | FBWS</title>
@endsection

@section('content')
    @php
        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        $k = $reportPayload['kpis'] ?? [];
        $b = $reportPayload['breakdowns'] ?? [];
        $tables = $reportPayload['tables'] ?? [];
    @endphp

    <style>
        .report-hero {
            background: white;
            border-radius: 16px;
            overflow: hidden;
        }

        .kpi-card {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 8px 22px rgba(15, 23, 42, .08);
            position: relative;
            overflow: hidden;
        }

        .kpi-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .kpi-accent::after {
            content: "";
            position: absolute;
            right: -24px;
            top: -24px;
            width: 90px;
            height: 90px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .25);
        }

        .report-panel {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(2, 6, 23, .06);
        }

        .chart-wrap-md {
            position: relative;
            height: 260px;
        }

        .chart-wrap-sm {
            position: relative;
            height: 220px;
            max-width: 340px;
            margin: 0 auto;
        }
    </style>

    <div class="card report-hero mb-3 border-0">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 style="font-size:1.5rem;font-weight:600;margin:0;">Monthly Reports & Analytics</h1>
                <small class="text-muted">
                    {{ $isAdmin ? 'Admin view (all modules)' : 'My monthly analytics (personal scope)' }}
                </small>
            </div>
            <form method="GET" action="{{ route('reports.monthly') }}" class="d-flex align-items-center gap-2">
                <select name="month" class="form-select form-select-sm" style="min-width:150px;">
                    @foreach ($months as $mNum => $mLabel)
                        <option value="{{ $mNum }}" {{ (int) $month === (int) $mNum ? 'selected' : '' }}>
                            {{ $mLabel }}
                        </option>
                    @endforeach
                </select>
                <select name="year" class="form-select form-select-sm" style="min-width:120px;">
                    @foreach ($yearOptions as $y)
                        <option value="{{ $y }}" {{ (int) $year === (int) $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach
                </select>
                <button class="btn btn-sm" style="background:#F7721E;color:#fff;">Apply</button>
            </form>
        </div>
        <div class="card-body p-3">
            <div class="d-flex flex-wrap gap-2">
                <span class="badge bg-light text-dark px-3 py-2">Month: {{ $months[$month] ?? $month }}</span>
                <span class="badge bg-light text-dark px-3 py-2">Year: {{ $year }}</span>
                <span class="badge bg-light text-dark px-3 py-2">Scope: {{ $isAdmin ? 'Admin Full' : 'My Data' }}</span>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3 col-sm-6">
            <div class="card kpi-card kpi-accent h-100" style="background:linear-gradient(135deg,#f97316,#fb923c);color:#fff;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="small opacity-75">Payments Collected</div>
                        <span class="kpi-icon" style="background:rgba(255,255,255,.2);"><i class="ti ti-cash"></i></span>
                    </div>
                    <div class="h5 mb-0">Rs {{ number_format((float) ($k['payments_amount'] ?? 0), 0) }}</div>
                    <small class="opacity-75">{{ (int) ($k['payments_count'] ?? 0) }} records</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card kpi-card kpi-accent h-100" style="background:linear-gradient(135deg,#0ea5e9,#38bdf8);color:#fff;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="small opacity-75">Welfare Net</div>
                        <span class="kpi-icon" style="background:rgba(255,255,255,.2);"><i class="ti ti-chart-donut"></i></span>
                    </div>
                    <div class="h5 mb-0">Rs {{ number_format((float) ($k['welfare_net'] ?? 0), 0) }}</div>
                    <small class="opacity-75">Income - Expense</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card kpi-card kpi-accent h-100" style="background:linear-gradient(135deg,#22c55e,#4ade80);color:#fff;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="small opacity-75">Orders / Deliveries</div>
                        <span class="kpi-icon" style="background:rgba(255,255,255,.2);"><i class="ti ti-truck-delivery"></i></span>
                    </div>
                    <div class="h5 mb-0">{{ (int) ($k['orders_count'] ?? 0) }} / {{ (int) ($k['deliveries_count'] ?? 0) }}</div>
                    <small class="opacity-75">Monthly count</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card kpi-card kpi-accent h-100" style="background:linear-gradient(135deg,#a855f7,#c084fc);color:#fff;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="small opacity-75">Leads / Complaints</div>
                        <span class="kpi-icon" style="background:rgba(255,255,255,.2);"><i class="ti ti-users-group"></i></span>
                    </div>
                    <div class="h5 mb-0">{{ (int) ($k['leads_count'] ?? 0) }} / {{ (int) ($k['complaints_count'] ?? 0) }}</div>
                    <small class="opacity-75">Monthly count</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-lg-2 col-md-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2">
                    <div class="small text-muted">Orders</div>
                    <div class="fw-bold">{{ (int) ($k['orders_count'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2">
                    <div class="small text-muted">Deliveries</div>
                    <div class="fw-bold">{{ (int) ($k['deliveries_count'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2">
                    <div class="small text-muted">Damages</div>
                    <div class="fw-bold">{{ (int) ($k['damages_count'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2">
                    <div class="small text-muted">Complaints</div>
                    <div class="fw-bold">{{ (int) ($k['complaints_count'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2">
                    <div class="small text-muted">Gallery</div>
                    <div class="fw-bold">{{ (int) ($k['gallery_uploads'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2">
                    <div class="small text-muted">Leads</div>
                    <div class="fw-bold">{{ (int) ($k['leads_count'] ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-8">
            <div class="card report-panel h-100">
                <div class="card-header bg-white border-bottom">
                    <strong>Yearly Trend ({{ $year }})</strong>
                </div>
                <div class="card-body">
                    <div class="chart-wrap-md">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card report-panel h-100">
                <div class="card-header bg-white border-bottom">
                    <strong>Module Contribution</strong>
                </div>
                <div class="card-body">
                    <div class="chart-wrap-sm">
                        <canvas id="moduleChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card report-panel h-100">
                <div class="card-header bg-white border-bottom"><strong>Orders Status Breakdown</strong></div>
                <div class="card-body">
                    <div class="chart-wrap-sm">
                        <canvas id="ordersStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card report-panel h-100">
                <div class="card-header bg-white border-bottom"><strong>Leads Status Breakdown</strong></div>
                <div class="card-body">
                    <div class="chart-wrap-sm">
                        <canvas id="leadsStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card report-panel mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="small text-muted mb-1">Welfare Income</div>
                    <div class="progress" style="height:9px;"><div class="progress-bar bg-success" style="width:100%"></div></div>
                    <div class="fw-semibold mt-1">Rs {{ number_format((float)($k['welfare_income'] ?? 0),0) }}</div>
                </div>
                <div class="col-md-4">
                    <div class="small text-muted mb-1">Welfare Expense</div>
                    <div class="progress" style="height:9px;"><div class="progress-bar bg-danger" style="width:100%"></div></div>
                    <div class="fw-semibold mt-1">Rs {{ number_format((float)($k['welfare_expense'] ?? 0),0) }}</div>
                </div>
                <div class="col-md-4">
                    <div class="small text-muted mb-1">Damages Fine</div>
                    <div class="progress" style="height:9px;"><div class="progress-bar bg-warning" style="width:100%"></div></div>
                    <div class="fw-semibold mt-1">Rs {{ number_format((float)($k['damages_fine'] ?? 0),0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card report-panel">
        <div class="card-header bg-white border-bottom">
            <strong>Detailed Monthly Tables</strong>
        </div>
        <div class="card-body">
            @php
                $moduleTotals = [
                    'payments' => (int) ($k['payments_count'] ?? 0),
                    'orders' => (int) ($k['orders_count'] ?? 0),
                    'deliveries' => (int) ($k['deliveries_count'] ?? 0),
                    'damages' => (int) ($k['damages_count'] ?? 0),
                    'complaints' => (int) ($k['complaints_count'] ?? 0),
                    'gallery' => (int) ($k['gallery_uploads'] ?? 0),
                    'leads' => (int) ($k['leads_count'] ?? 0),
                ];
                $maxTotal = max(1, max($moduleTotals));
            @endphp

            <div class="row g-2 mb-3">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="small text-muted mb-2">Orders Status Visual</div>
                            @foreach (($b['orders_status'] ?? []) as $st => $val)
                                @php
                                    $pct = (int) round(($val / max(1, array_sum($b['orders_status'] ?? [1]))) * 100);
                                @endphp
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>{{ ucfirst($st) }}</span>
                                    <span>{{ (int) $val }} ({{ $pct }}%)</span>
                                </div>
                                <div class="progress mb-2" style="height:7px;">
                                    <div class="progress-bar"
                                        style="width: {{ $pct }}%; background: {{ $st === 'delivered' ? '#22c55e' : ($st === 'confirmed' ? '#0ea5e9' : ($st === 'cancelled' ? '#ef4444' : '#f59e0b')) }};">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="small text-muted mb-2">Leads Status Visual</div>
                            @foreach (($b['leads_status'] ?? []) as $st => $val)
                                @php
                                    $pct = (int) round(($val / max(1, array_sum($b['leads_status'] ?? [1]))) * 100);
                                @endphp
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>{{ ucfirst($st) }}</span>
                                    <span>{{ (int) $val }} ({{ $pct }}%)</span>
                                </div>
                                <div class="progress mb-2" style="height:7px;">
                                    <div class="progress-bar"
                                        style="width: {{ $pct }}%; background: {{ $st === 'approved' ? '#22c55e' : ($st === 'contacted' ? '#0ea5e9' : ($st === 'rejected' ? '#ef4444' : '#f59e0b')) }};">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-2 mb-3">
                @foreach (
                    [
                        ['key' => 'payments', 'label' => 'Payments', 'color' => '#f97316'],
                        ['key' => 'orders', 'label' => 'Orders', 'color' => '#0ea5e9'],
                        ['key' => 'deliveries', 'label' => 'Deliveries', 'color' => '#22c55e'],
                        ['key' => 'damages', 'label' => 'Damages/Fines', 'color' => '#ef4444'],
                        ['key' => 'complaints', 'label' => 'Complaints', 'color' => '#a855f7'],
                        ['key' => 'gallery', 'label' => 'Gallery Uploads', 'color' => '#64748b'],
                        ['key' => 'leads', 'label' => 'Leads', 'color' => '#f59e0b'],
                    ] as $m
                )
                    @php
                        $count = (int) ($moduleTotals[$m['key']] ?? 0);
                        $pct = (int) round(($count / $maxTotal) * 100);
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="small fw-semibold">{{ $m['label'] }}</div>
                                    <span class="badge bg-light text-dark">{{ $count }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%; background: {{ $m['color'] }};">
                                    </div>
                                </div>
                                <div class="text-muted small mt-1">Activity score: {{ $pct }}%</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="accordion" id="reportsAccordion">
                @php
                    $sections = [
                        'payments' => 'Payments',
                        'orders' => 'Orders',
                        'deliveries' => 'Deliveries',
                        'damages' => 'Damages/Fines',
                        'complaints' => 'Complaints',
                        'gallery' => 'Gallery Uploads',
                        'leads' => 'Leads',
                    ];
                @endphp
                @foreach ($sections as $key => $label)
                    @php $rows = $tables[$key] ?? collect(); @endphp
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $key }}">
                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $key }}">
                                {{ $label }} ({{ is_countable($rows) ? count($rows) : 0 }})
                            </button>
                        </h2>
                        <div id="collapse{{ $key }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                            data-bs-parent="#reportsAccordion">
                            <div class="accordion-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <tbody>
                                            @forelse ($rows as $r)
                                                <tr>
                                                    <td>
                                                        @if ($key === 'payments')
                                                            #{{ $r->id }} | {{ $r->user->name ?? 'N/A' }} |
                                                            Rs {{ number_format((float) $r->amount, 0) }} |
                                                            {{ $r->date }}
                                                        @elseif($key === 'orders')
                                                            #{{ $r->id }} | {{ $r->user->name ?? 'N/A' }} |
                                                            {{ ucfirst((string) $r->status) }} |
                                                            {{ $r->created_at?->format('d M Y h:i A') }}
                                                        @elseif($key === 'deliveries')
                                                            #{{ $r->id }} | {{ $r->user->name ?? 'N/A' }} |
                                                            {{ $r->delivery_date }} {{ $r->delivery_time }}
                                                        @elseif($key === 'damages')
                                                            #{{ $r->id }} | {{ $r->user->name ?? 'N/A' }} |
                                                            Qty {{ (int) $r->qty }} | Fine Rs
                                                            {{ number_format((float) $r->fine, 0) }}
                                                        @elseif($key === 'complaints')
                                                            #{{ $r->id }} | {{ $r->user->name ?? 'Anonymous' }} |
                                                            {{ ucfirst((string) $r->type) }} /
                                                            {{ ucfirst((string) $r->status) }} |
                                                            {{ $r->subject }}
                                                        @elseif($key === 'gallery')
                                                            #{{ $r->id }} | {{ $r->user->name ?? 'N/A' }} |
                                                            {{ $r->title ?: 'Untitled' }} |
                                                            {{ $r->created_at?->format('d M Y') }}
                                                        @elseif($key === 'leads')
                                                            #{{ $r->id }} | {{ $r->name }} | {{ $r->phone }} |
                                                            {{ ucfirst((string) $r->status) }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-muted text-center py-3">No records found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const payload = @json($reportPayload);
        const trend = payload.trend || {};

        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const trendGradient = trendCtx.createLinearGradient(0, 0, 0, 220);
        trendGradient.addColorStop(0, 'rgba(247,114,30,0.35)');
        trendGradient.addColorStop(1, 'rgba(247,114,30,0.02)');
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: trend.labels || [],
                datasets: [{
                        label: 'Payments Amount',
                        data: trend.paymentsAmount || [],
                        borderColor: '#F7721E',
                        backgroundColor: trendGradient,
                        fill: true,
                        tension: .35,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Orders',
                        data: trend.ordersCount || [],
                        borderColor: '#0ea5e9',
                        backgroundColor: 'rgba(14,165,233,0.12)',
                        tension: .35,
                        yAxisID: 'y1'
                    },
                    {
                        label: 'Complaints',
                        data: trend.complaintsCount || [],
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239,68,68,0.12)',
                        tension: .35,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        position: 'left'
                    },
                    y1: {
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });

        const moduleData = payload.moduleContribution || {
            labels: [],
            values: []
        };
        new Chart(document.getElementById('moduleChart'), {
            type: 'bar',
            data: {
                labels: moduleData.labels,
                datasets: [{
                    label: 'Count',
                    data: moduleData.values,
                    backgroundColor: ['#F7721E', '#0ea5e9', '#22c55e', '#ef4444', '#a855f7', '#64748b', '#f59e0b']
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                datasets: {
                    bar: {
                        borderRadius: 8,
                        maxBarThickness: 28
                    }
                }
            }
        });

        const ordersStatus = (payload.breakdowns && payload.breakdowns.orders_status) ? payload.breakdowns.orders_status : {};
        new Chart(document.getElementById('ordersStatusChart'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(ordersStatus).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
                datasets: [{
                    data: Object.values(ordersStatus),
                    backgroundColor: ['#f59e0b', '#0ea5e9', '#22c55e', '#ef4444']
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '58%',
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });

        const leadsStatus = (payload.breakdowns && payload.breakdowns.leads_status) ? payload.breakdowns.leads_status : {};
        new Chart(document.getElementById('leadsStatusChart'), {
            type: 'pie',
            data: {
                labels: Object.keys(leadsStatus).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
                datasets: [{
                    data: Object.values(leadsStatus),
                    backgroundColor: ['#f59e0b', '#0ea5e9', '#22c55e', '#ef4444']
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>
@endsection

