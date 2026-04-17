@extends('home')

@section('title')
    <title>Create Complaint / Suggestion | FBWS</title>
@endsection

@section('content')
    <style>
        /* ====== Page Theme ====== */
        :root {
            --accent: #F7721E;
            --bg: #f6f8fc;
            --text: #0f172a;
            --muted: #6b7280;
            --line: #e9eef6;
            --shadow: 0 14px 40px rgba(2, 6, 23, .08);
            --r: 18px;
        }

        body {
            background: var(--bg);
        }

        .page-wrap {
            padding: 18px 0;
        }

        .headbar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .headbar h4 {
            margin: 0;
            font-weight: 900;
            color: var(--text);
        }

        .headbar small {
            color: var(--muted);
            display: block;
            margin-top: 4px;
            line-height: 1.4;
        }

        .btn-accent {
            background: var(--accent);
            color: #fff;
            border: none;
        }

        .btn-accent:hover {
            filter: brightness(.95);
            color: #fff;
        }

        /* ====== Card ====== */
        .form-shell {
            border: 1px solid var(--line);
            border-radius: var(--r);
            background: #fff;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .form-top {
            padding: 16px 18px;
            border-bottom: 1px solid var(--line);
            background: linear-gradient(180deg, rgba(247, 114, 30, .10), rgba(247, 114, 30, 0));
        }

        .form-top .badgex {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid rgba(247, 114, 30, .22);
            background: rgba(247, 114, 30, .08);
            font-weight: 800;
            color: #8a3b0f;
            font-size: 12px;
        }

        .form-top .hint {
            margin-top: 10px;
            color: var(--muted);
            font-size: 12px;
        }

        .form-body {
            padding: 18px;
        }

        /* ====== Inputs ====== */
        .field-label {
            font-weight: 800;
            color: var(--text);
            font-size: 13px;
            margin-bottom: 6px;
        }

        .field-help {
            color: var(--muted);
            font-size: 12px;
            margin-top: 6px;
        }

        .form-control,
        .form-select {
            border-radius: 14px;
            border: 1px solid var(--line);
            padding: 11px 12px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgba(247, 114, 30, .45);
            box-shadow: 0 0 0 .2rem rgba(247, 114, 30, .12);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 140px;
        }

        /* ====== Footer actions ====== */
        .form-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            justify-content: flex-end;
            padding-top: 10px;
        }

        .btn-soft {
            border: 1px solid var(--line);
            background: #fff;
            color: var(--text);
        }

        .btn-soft:hover {
            background: #f8fafc;
        }

        /* ====== Nice "type" toggle look ====== */
        .type-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .type-pill {
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 12px;
            cursor: pointer;
            display: flex;
            gap: 10px;
            align-items: flex-start;
            background: #fff;
            transition: .2s ease;
        }

        .type-pill:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(2, 6, 23, .06);
            border-color: rgba(247, 114, 30, .25);
        }

        .type-pill .dot {
            width: 36px;
            height: 36px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            border: 1px solid rgba(2, 6, 23, .08);
            background: #f8fafc;
        }

        .type-pill .t1 {
            font-weight: 900;
            font-size: 13px;
            margin: 0;
            color: var(--text);
        }

        .type-pill .t2 {
            margin: 4px 0 0;
            font-size: 12px;
            color: var(--muted);
            line-height: 1.35;
        }

        .type-pill input {
            display: none;
        }

        .type-pill.active {
            border-color: rgba(247, 114, 30, .45);
            background: rgba(247, 114, 30, .06);
        }

        .type-pill.active .dot {
            background: rgba(247, 114, 30, .10);
            border-color: rgba(247, 114, 30, .25);
            color: #8a3b0f;
        }

        /* ===== Responsive ===== */
        @media (max-width: 576px) {
            .form-top {
                padding: 14px;
            }

            .form-body {
                padding: 14px;
            }

            .type-wrap {
                grid-template-columns: 1fr;
            }

            .headbar {
                flex-direction: column;
                align-items: stretch;
            }

            .headbar .btn {
                width: 100%;
            }

            .form-actions {
                justify-content: stretch;
            }

            .form-actions .btn {
                width: 100%;
            }
        }
    </style>

    <div class="container page-wrap" style="max-width: 860px;">

        <div class="headbar">
            <div>
                <h4>Complaint / Suggestion</h4>
                <small>Apni شکایت یا تجویز detail ke sath submit karein.</small>
            </div>

            <a href="{{ route('complaints.mine') }}" class="btn btn-soft">
                <i class="ti ti-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="form-shell">
            <div class="form-top">
                <span class="badgex">
                    <i class="ti ti-message-report"></i>
                    Complaint / Suggestion Form
                </span>
                <div class="hint">
                    Tip: Subject clear rakhein aur message me complete detail likhein.
                </div>
            </div>

            <div class="form-body">
                <form method="POST" action="{{ route('complaints.store') }}">
                    @csrf

                    <div class="row g-3">

                        {{-- Type (Better UI) --}}
                        <div class="col-12">
                            <div class="field-label">Type <span class="text-danger">*</span></div>

                            @php
                                $oldType = old('type', 'complaint');
                            @endphp

                            <div class="type-wrap" id="typeWrap">
                                <label class="type-pill {{ $oldType === 'complaint' ? 'active' : '' }}"
                                    data-val="complaint">
                                    <input type="radio" name="type" value="complaint"
                                        {{ $oldType === 'complaint' ? 'checked' : '' }}>
                                    <div class="dot">C</div>
                                    <div>
                                        <p class="t1">Complaint</p>
                                        <p class="t2">Masla / issue report karein (e.g. missing item, record problem).
                                        </p>
                                    </div>
                                </label>

                                <label class="type-pill {{ $oldType === 'suggestion' ? 'active' : '' }}"
                                    data-val="suggestion">
                                    <input type="radio" name="type" value="suggestion"
                                        {{ $oldType === 'suggestion' ? 'checked' : '' }}>
                                    <div class="dot">S</div>
                                    <div>
                                        <p class="t1">Suggestion</p>
                                        <p class="t2">Behtari ke liye idea / recommendation share karein.</p>
                                    </div>
                                </label>
                            </div>

                            @error('type')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Subject --}}
                        <div class="col-12">
                            <label class="field-label">Subject <span class="text-danger">*</span></label>
                            <input name="subject" class="form-control @error('subject') is-invalid @enderror"
                                value="{{ old('subject') }}" placeholder="e.g. Missing plates record">
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="field-help">Short + clear likhein, taake admin quickly samajh jaye.</div>
                        </div>

                        {{-- Message --}}
                        <div class="col-12">
                            <label class="field-label">Message <span class="text-danger">*</span></label>
                            <textarea name="message" rows="6" class="form-control @error('message') is-invalid @enderror"
                                placeholder="Write complete details...">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="field-help">Details: kab / kahan / kya hua, agar koi reference ho to mention kar
                                dein.</div>
                        </div>

                        {{-- Actions --}}
                        <div class="col-12">
                            <div class="form-actions">
                                <button class="btn btn-accent px-4">
                                    <i class="ti ti-send me-1"></i> Submit
                                </button>

                                <button type="reset" class="btn btn-soft px-4" id="resetBtn">
                                    Reset
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Type pills active toggle --}}
    <script>
        (function() {
            const wrap = document.getElementById('typeWrap');
            if (!wrap) return;

            wrap.addEventListener('click', function(e) {
                const pill = e.target.closest('.type-pill');
                if (!pill) return;

                document.querySelectorAll('.type-pill').forEach(p => p.classList.remove('active'));
                pill.classList.add('active');

                const radio = pill.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
            });

            // Reset: default back to complaint
            const resetBtn = document.getElementById('resetBtn');
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    setTimeout(() => {
                        document.querySelectorAll('.type-pill').forEach(p => p.classList.remove(
                            'active'));
                        const first = document.querySelector('.type-pill[data-val="complaint"]');
                        if (first) {
                            first.classList.add('active');
                            const r = first.querySelector('input[type="radio"]');
                            if (r) r.checked = true;
                        }
                    }, 0);
                });
            }
        })();
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'کامیابی!',
                text: @json(session('success')),
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'اوہ!',
                text: @json($errors->first()),
            });
        </script>
    @endif
@endsection
