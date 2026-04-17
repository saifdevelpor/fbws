@extends('home')

@section('title')
    <title>Leads | FBWS</title>
@endsection

@section('content')

    @if (session('lead_wa_link'))
        <div class="alert alert-info d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <strong>WhatsApp not opened automatically?</strong>
                <span class="ms-1">Click the button to open it.</span>
            </div>
            <a href="{{ session('lead_wa_link') }}" target="_blank" class="btn btn-sm btn-success">
                <i class="ti ti-brand-whatsapp me-1"></i> Open WhatsApp
            </a>
        </div>
    @endif

    <div class="card shadow-sm border-0">

        {{-- HEADER --}}
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 style="font-size:1.5rem;font-weight:600;margin:0;">Leads</h1>
                <small class="text-muted">Become a Part form submissions</small>
            </div>

            <form method="GET" action="{{ url()->current() }}" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" style="min-width:160px;">
                    <option value="">All Status</option>
                    @foreach ($statuses as $st)
                        <option value="{{ $st }}" {{ $status === $st ? 'selected' : '' }}>
                            {{ ucfirst($st) }}
                        </option>
                    @endforeach
                </select>

                <button class="btn btn-sm text-white" style="background:#F7721E;">Filter</button>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('leads.index') }}">Reset</a>
            </form>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>CNIC</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($leads as $index => $lead)
                            @php
                                $statusClass = match ($lead->status) {
                                    'contacted' => 'bg-info',
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    default => 'bg-warning text-dark',
                                };

                                // USER WHATSAPP NUMBER FORMAT
                                $leadDigits = preg_replace('/\D+/', '', (string) $lead->phone);

                                if (preg_match('/^0\d{10}$/', $leadDigits)) {
                                    $leadDigits = '92' . substr($leadDigits, 1);
                                }

                                $leadWaText = rawurlencode(
                                    \App\Support\LeadWhatsAppMessage::listOutreach($lead),
                                );

                                $leadWaLink = $leadDigits ? "https://wa.me/{$leadDigits}?text={$leadWaText}" : null;
                            @endphp

                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <div class="fw-semibold">{{ $lead->name }}</div>
                                    <small class="text-muted">Father: {{ $lead->father_name }}</small>
                                </td>

                                <td>
                                    <div>{{ $lead->phone }}</div>
                                    <small class="text-muted">{{ $lead->email }}</small>
                                </td>

                                <td>{{ $lead->id_card }}</td>

                                <td>
                                    <span class="badge rounded-pill {{ $statusClass }}">
                                        {{ ucfirst($lead->status) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="lead-local-time text-nowrap"
                                        data-at="{{ optional($lead->created_at)->toIso8601String() }}">
                                        {{ $lead->created_at?->timezone(config('app.timezone'))->format('d M Y, h:i A') }}
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex gap-2">

                                        {{-- VIEW --}}
                                        <button class="btn btn-sm btn-light border" data-bs-toggle="modal"
                                            data-bs-target="#leadDetails{{ $lead->id }}">
                                            <i class="ti ti-eye"></i>
                                        </button>

                                        {{-- WHATSAPP USER --}}
                                        @if ($leadWaLink)
                                            <a href="{{ $leadWaLink }}" target="_blank" class="btn btn-sm btn-success">
                                                <i class="ti ti-brand-whatsapp"></i>
                                            </a>
                                        @endif

                                    </div>
                                </td>
                            </tr>

                            {{-- MODAL --}}
                            <div class="modal fade" id="leadDetails{{ $lead->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">

                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title">Lead #{{ $lead->id }}</h5>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">

                                            {{-- DETAILS --}}
                                            <div class="row g-3">

                                                @foreach ([
                                                    'Name' => $lead->name,
                                                    'Father Name' => $lead->father_name,
                                                    'Phone' => $lead->phone,
                                                    'Email' => $lead->email,
                                                    'CNIC' => $lead->id_card,
                                                ] as $label => $value)
                                                    <div class="col-md-6">
                                                        <div class="card border-0 shadow-sm">
                                                            <div class="card-body py-2">
                                                                <small class="text-muted">{{ $label }}</small>
                                                                <div class="fw-semibold">{{ $value }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                                <div class="col-md-6">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body py-2">
                                                            <small class="text-muted">Submitted At</small>
                                                            <div class="fw-semibold lead-local-time text-nowrap"
                                                                data-at="{{ optional($lead->created_at)->toIso8601String() }}">
                                                                {{ $lead->created_at?->timezone(config('app.timezone'))->format('d M Y, h:i A') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body">
                                                            <small class="text-muted">Address</small>
                                                            <div class="fw-semibold">{{ $lead->address }}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body">
                                                            <small class="text-muted">Message</small>
                                                            <div class="fw-semibold">{{ $lead->message }}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <hr>

                                            {{-- UPDATE + WHATSAPP SEND --}}
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body bg-white bg-opacity-10 rounded">

                                                    <form id="leadForm{{ $lead->id }}"
                                                        action="{{ route('leads.update', $lead->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')

                                                        <div class="row g-2">

                                                            <div class="col-md-4">
                                                                <label class="form-label small text-muted">Status</label>
                                                                <select name="status" class="form-select form-select-sm">
                                                                    @foreach ($statuses as $st)
                                                                        <option value="{{ $st }}"
                                                                            {{ $lead->status === $st ? 'selected' : '' }}>
                                                                            {{ ucfirst($st) }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-8">
                                                                <label class="form-label small text-muted">Admin
                                                                    Note</label>

                                                                <textarea name="admin_note" class="form-control form-control-sm bg-warning bg-opacity-25 border-warning"
                                                                    placeholder="Add admin note" rows="3">{{ $lead->admin_note }}</textarea>
                                                            </div>

                                                        </div>

                                                        <div class="d-flex justify-content-end gap-2 mt-3">

                                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                                data-bs-dismiss="modal">Close</button>

                                                            <button type="submit" class="btn btn-sm text-white"
                                                                style="background:#F7721E;">
                                                                Save
                                                            </button>

                                                        </div>

                                                    </form>

                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>

                        @empty
                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.lead-local-time[data-at]').forEach(function(el) {
                var iso = el.getAttribute('data-at');
                if (!iso) return;
                var d = new Date(iso);
                if (isNaN(d.getTime())) return;
                el.textContent = d.toLocaleString('en-GB', {
                    timeZone: @json(config('app.timezone')),
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: @json(session('success')),
                timer: 2200,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('lead_wa_link'))
        <script>
            (function() {
                const url = @json(session('lead_wa_link'));
                const opened = window.open(url, '_blank');

                if (!opened || opened.closed || typeof opened.closed === 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Open WhatsApp',
                        text: 'Popup blocked by browser. Click the Open WhatsApp button.',
                        confirmButtonText: 'OK'
                    });
                }
            })();
        </script>
    @endif
@endsection
