@extends('home')

@section('title')
    <title>Support Request Detail | FBWS</title>
@endsection

@section('content')
    <style>
        :root { --accent:#F7721E; --line:#e5e7eb; --muted:#64748b; --shadow:0 14px 36px rgba(15,23,42,.06); }
        .detail-wrap { max-width: 1180px; margin: 0 auto; padding: 18px 14px 28px; }
        .shell { background:#fff; border:1px solid var(--line); border-radius:22px; box-shadow:var(--shadow); overflow:hidden; }
        .hero { padding:20px; border-bottom:1px solid var(--line); background:linear-gradient(135deg, rgba(247,114,30,.12), rgba(255,255,255,0) 70%); }
        .info-box { border:1px solid #eef2f7; border-radius:14px; padding:12px 14px; background:#fff; height:100%; }
        .labelx { font-size:12px; color:var(--muted); margin-bottom:5px; }
        .valuex { font-weight:800; color:#0f172a; }
    </style>

    <div class="detail-wrap">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <div>
                <h4 class="mb-1 fw-bold">Support Request Detail</h4>
                <div class="text-muted small">Admin review panel for member welfare assistance case.</div>
            </div>
            <a href="{{ route('admin.support-requests.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="shell">
            <div class="hero">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <div class="small text-muted mb-1">Request #{{ $supportRequest->id }}</div>
                        <h3 class="mb-1 fw-bold">{{ $supportRequest->title }}</h3>
                        <div class="text-muted">Submitted by {{ $supportRequest->user?->name ?? 'Member' }} on {{ $supportRequest->created_at?->format('d M Y, h:i A') }}</div>
                    </div>
                    <div class="text-end">
                        <div class="badge bg-light text-dark border text-capitalize mb-1">{{ str_replace('_', ' ', $supportRequest->status) }}</div>
                        <div class="badge text-bg-warning text-capitalize">{{ $supportRequest->priority }} priority</div>
                    </div>
                </div>
            </div>

            <div class="p-3 p-md-4">
                <div class="row g-3 mb-3">
                    <div class="col-md-3"><div class="info-box"><div class="labelx">Category</div><div class="valuex text-capitalize">{{ $supportRequest->category }}</div></div></div>
                    <div class="col-md-3"><div class="info-box"><div class="labelx">Support Type</div><div class="valuex text-capitalize">{{ str_replace('_', ' ', $supportRequest->support_type) }}</div></div></div>
                    <div class="col-md-3"><div class="info-box"><div class="labelx">Amount Needed</div><div class="valuex">{{ $supportRequest->amount_needed ? 'Rs ' . number_format((float) $supportRequest->amount_needed, 2) : 'Not specified' }}</div></div></div>
                    <div class="col-md-3"><div class="info-box"><div class="labelx">Contact Number</div><div class="valuex">{{ $supportRequest->contact_number ?: ($supportRequest->user?->phone_number ?? 'Not available') }}</div></div></div>
                </div>

                <div class="row g-3">
                    <div class="col-lg-7">
                        <div class="info-box h-100">
                            <div class="labelx">Member Details</div>
                            <div class="valuex mb-2">{{ $supportRequest->user?->name ?? 'Member' }}</div>
                            <div class="small text-muted mb-3">{{ $supportRequest->user?->email ?? 'Email not available' }}</div>
                            <div style="white-space: pre-wrap; color:#334155; line-height:1.7;">{{ $supportRequest->details }}</div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="info-box">
                            <div class="labelx">Review & Update</div>
                            <form method="POST" action="{{ route('admin.support-requests.update', \Illuminate\Support\Facades\Crypt::encryptString((string) $supportRequest->id)) }}">
                                @csrf
                                @method('PATCH')

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="new" @selected($supportRequest->status === 'new')>New</option>
                                        <option value="under_review" @selected($supportRequest->status === 'under_review')>Under Review</option>
                                        <option value="approved" @selected($supportRequest->status === 'approved')>Approved</option>
                                        <option value="fulfilled" @selected($supportRequest->status === 'fulfilled')>Fulfilled</option>
                                        <option value="rejected" @selected($supportRequest->status === 'rejected')>Rejected</option>
                                    </select>
                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Priority</label>
                                    <select name="priority" class="form-select @error('priority') is-invalid @enderror">
                                        <option value="low" @selected($supportRequest->priority === 'low')>Low</option>
                                        <option value="medium" @selected($supportRequest->priority === 'medium')>Medium</option>
                                        <option value="high" @selected($supportRequest->priority === 'high')>High</option>
                                        <option value="urgent" @selected($supportRequest->priority === 'urgent')>Urgent</option>
                                    </select>
                                    @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Admin Note</label>
                                    <textarea name="admin_note" rows="7" class="form-control @error('admin_note') is-invalid @enderror" placeholder="Internal notes, action summary, ya member ko dene wali update...">{{ old('admin_note', $supportRequest->admin_note) }}</textarea>
                                    @error('admin_note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <button class="btn text-white" style="background:#F7721E;">
                                    <i class="ti ti-device-floppy me-1"></i> Save Update
                                </button>
                            </form>
                        </div>

                        <div class="info-box mt-3">
                            <div class="labelx">Timeline</div>
                            <div class="small text-muted mb-2">Created: {{ $supportRequest->created_at?->format('d M Y, h:i A') }}</div>
                            <div class="small text-muted mb-2">Reviewed: {{ $supportRequest->reviewed_at?->format('d M Y, h:i A') ?? 'Not yet' }}</div>
                            <div class="small text-muted">Closed: {{ $supportRequest->resolved_at?->format('d M Y, h:i A') ?? 'Still active' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
