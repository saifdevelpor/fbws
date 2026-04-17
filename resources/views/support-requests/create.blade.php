@extends('home')

@section('title')
    <title>New Support Request | FBWS</title>
@endsection

@section('content')
    <style>
        :root {
            --accent: #F7721E;
            --ink: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
            --bg: #f8fafc;
            --panel: #ffffff;
            --shadow: 0 18px 45px rgba(15, 23, 42, .08);
        }
        body { background: linear-gradient(180deg, #fffaf5 0%, var(--bg) 45%, #f8fafc 100%); }
        .support-wrap { max-width: 980px; margin: 0 auto; padding: 20px 14px 30px; }
        .support-shell { background: var(--panel); border: 1px solid var(--line); border-radius: 22px; box-shadow: var(--shadow); overflow: hidden; }
        .support-hero { padding: 22px; background: linear-gradient(135deg, rgba(247,114,30,.12), rgba(255,255,255,0) 70%); border-bottom: 1px solid var(--line); }
        .support-hero h3 { margin: 8px 0 6px; font-weight: 900; color: var(--ink); }
        .support-hero p { margin: 0; color: var(--muted); max-width: 640px; }
        .mini-badge { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; font-size:12px; font-weight:800; color:#9a3412; background:rgba(247,114,30,.1); border:1px solid rgba(247,114,30,.2); }
        .support-body { padding: 22px; }
        .labelx { font-weight: 800; color: var(--ink); font-size: 13px; margin-bottom: 7px; }
        .hintx { font-size: 12px; color: var(--muted); margin-top: 6px; }
        .form-control, .form-select { border-radius: 14px; border-color: var(--line); padding: 11px 12px; }
        .form-control:focus, .form-select:focus { border-color: rgba(247,114,30,.45); box-shadow: 0 0 0 .2rem rgba(247,114,30,.12); }
        textarea.form-control { min-height: 150px; resize: vertical; }
        .type-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px; }
        .chip-card { border: 1px solid var(--line); border-radius: 16px; padding: 14px; cursor: pointer; transition: .2s ease; background: #fff; height: 100%; }
        .chip-card:hover { transform: translateY(-2px); border-color: rgba(247,114,30,.28); box-shadow: 0 12px 28px rgba(15,23,42,.06); }
        .chip-card.active { border-color: rgba(247,114,30,.5); background: rgba(247,114,30,.05); }
        .chip-card input { display: none; }
        .chip-title { font-weight: 900; color: var(--ink); font-size: 13px; margin-bottom: 4px; }
        .chip-text { color: var(--muted); font-size: 12px; line-height: 1.45; }
        .foot-actions { display:flex; justify-content:flex-end; gap:10px; flex-wrap:wrap; padding-top: 6px; }
        .btn-accent { background: var(--accent); color:#fff; border:none; }
        .btn-accent:hover { color:#fff; filter: brightness(.96); }
        .btn-soft { border:1px solid var(--line); background:#fff; color:var(--ink); }
        @media (max-width: 576px) {
            .support-hero, .support-body { padding: 16px; }
            .foot-actions .btn { width: 100%; }
        }
    </style>

    <div class="support-wrap">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <div>
                <h4 class="mb-1 fw-bold">Support Request Form</h4>
                <div class="text-muted small">Agar family ko welfare support chahiye ho to yahan se request submit karein.</div>
            </div>
            <a href="{{ route('support-requests.mine') }}" class="btn btn-soft">
                <i class="ti ti-arrow-left me-1"></i> My Requests
            </a>
        </div>

        <div class="support-shell">
            <div class="support-hero">
                <span class="mini-badge"><i class="ti ti-heart-handshake"></i> Welfare Support Desk</span>
                <h3>Apni zaroorat detail ke saath share karein</h3>
                <p>Yeh module financial, ration, medical, education ya emergency help requests ko organize karne ke liye add kiya gaya hai, taa ke admin priority ke saath unhein review kar sake.</p>
            </div>

            <div class="support-body">
                <form method="POST" action="{{ route('support-requests.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="labelx">Request Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Emergency medicine support for family">
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="labelx">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror">
                                <option value="">Select category</option>
                                <option value="medical" @selected(old('category') === 'medical')>Medical</option>
                                <option value="education" @selected(old('category') === 'education')>Education</option>
                                <option value="ration" @selected(old('category') === 'ration')>Ration</option>
                                <option value="emergency" @selected(old('category') === 'emergency')>Emergency</option>
                                <option value="other" @selected(old('category') === 'other')>Other</option>
                            </select>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="labelx">Support Type <span class="text-danger">*</span></label>
                            <select name="support_type" class="form-select @error('support_type') is-invalid @enderror">
                                <option value="">Select support type</option>
                                <option value="financial" @selected(old('support_type') === 'financial')>Financial</option>
                                <option value="goods" @selected(old('support_type') === 'goods')>Goods / Ration</option>
                                <option value="service" @selected(old('support_type') === 'service')>Service Help</option>
                                <option value="volunteer" @selected(old('support_type') === 'volunteer')>Volunteer Assistance</option>
                            </select>
                            @error('support_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <div class="labelx">Priority <span class="text-danger">*</span></div>
                            @php($oldPriority = old('priority', 'medium'))
                            <div class="type-grid" id="priorityGrid">
                                @foreach ([
                                    'low' => 'General follow-up request',
                                    'medium' => 'Needs attention in normal queue',
                                    'high' => 'Important case needing faster review',
                                    'urgent' => 'Immediate help required'
                                ] as $key => $text)
                                    <label class="chip-card {{ $oldPriority === $key ? 'active' : '' }}">
                                        <input type="radio" name="priority" value="{{ $key }}" {{ $oldPriority === $key ? 'checked' : '' }}>
                                        <div class="chip-title">{{ ucfirst($key) }}</div>
                                        <div class="chip-text">{{ $text }}</div>
                                    </label>
                                @endforeach
                            </div>
                            @error('priority')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="labelx">Amount Needed (Optional)</label>
                            <input type="number" step="0.01" min="0" name="amount_needed" value="{{ old('amount_needed') }}" class="form-control @error('amount_needed') is-invalid @enderror" placeholder="e.g. 15000">
                            @error('amount_needed')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="hintx">Agar cash support chahiye ho to approximate amount mention karein.</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="labelx">Contact Number</label>
                            <input type="text" name="contact_number" value="{{ old('contact_number', auth()->user()->phone_number ?? '') }}" class="form-control @error('contact_number') is-invalid @enderror" placeholder="03xx xxxxxxx">
                            @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="labelx">Full Details <span class="text-danger">*</span></label>
                            <textarea name="details" class="form-control @error('details') is-invalid @enderror" placeholder="Issue ka background, family condition, required help, aur urgency yahan likhein...">{{ old('details') }}</textarea>
                            @error('details')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <div class="foot-actions">
                                <button type="reset" class="btn btn-soft px-4">Reset</button>
                                <button type="submit" class="btn btn-accent px-4">
                                    <i class="ti ti-send me-1"></i> Submit Request
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('click', function (e) {
            const card = e.target.closest('#priorityGrid .chip-card');
            if (!card) return;

            document.querySelectorAll('#priorityGrid .chip-card').forEach(item => item.classList.remove('active'));
            card.classList.add('active');

            const radio = card.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
            }
        });
    </script>
@endsection
