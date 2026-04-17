<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E ID-Card | FBWS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo3.png') }}">
    <style>
        :root {
            --ink: #122033;
            --ink-soft: #506176;
            --line: #d6dfeb;
            --paper: #f3f6fb;
            --brand: #7f1010;
            --brand-deep: #5a0909;
            --accent: #f49a26;
            --shadow: 0 24px 60px rgba(15, 23, 42, .14);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(244, 154, 38, .16), transparent 24%),
                radial-gradient(circle at bottom right, rgba(127, 16, 16, .12), transparent 22%),
                linear-gradient(160deg, #eef3f9 0%, #dfe8f4 100%);
            padding: 28px 16px 40px;
        }

        .page-shell {
            max-width: 1200px;
            margin: 0 auto;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .toolbar-copy h1 {
            margin: 0;
            font-size: 30px;
            font-weight: 900;
            letter-spacing: -.03em;
        }

        .toolbar-copy p {
            margin: 6px 0 0;
            color: var(--ink-soft);
            font-size: 14px;
        }

        .toolbar-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .toolbar-actions a,
        .toolbar-actions button {
            border: 0;
            border-radius: 999px;
            padding: 12px 18px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .toolbar-actions a:hover,
        .toolbar-actions button:hover {
            transform: translateY(-2px);
        }

        .btn-back {
            color: var(--ink);
            background: #fff;
            box-shadow: 0 10px 25px rgba(15, 23, 42, .08);
        }

        .btn-download,
        .btn-print {
            color: #fff;
            box-shadow: 0 16px 34px rgba(127, 16, 16, .18);
        }

        .btn-download {
            background: linear-gradient(135deg, #2b5c88, #173656);
        }

        .btn-print {
            background: linear-gradient(135deg, var(--brand), var(--brand-deep));
        }

        .helper-note {
            margin: 0 0 18px;
            color: var(--ink-soft);
            font-size: 13px;
        }

        .cards-wrap {
            display: flex;
            justify-content: center;
        }

        .cards-sheet {
            width: 100%;
            max-width: 980px;
            padding: 28px;
            border-radius: 34px;
            background: rgba(255, 255, 255, .72);
            border: 1px solid rgba(255, 255, 255, .8);
            box-shadow: var(--shadow);
            backdrop-filter: blur(12px);
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(280px, 1fr));
            gap: 28px;
            align-items: start;
            justify-items: center;
        }

        .id-card {
            width: 100%;
            max-width: 340px;
            min-height: 540px;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 20px 40px rgba(15, 23, 42, .12);
        }

        .id-card::before {
            content: "";
            position: absolute;
            inset: 12px;
            border: 1px solid rgba(18, 32, 51, .08);
            border-radius: 14px;
            pointer-events: none;
        }

        .card-front .card-head {
            padding: 20px 20px 14px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
        }

        .brand-side {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .brand-mark {
            width: 58px;
            height: 58px;
            object-fit: contain;
            padding: 4px;
            border-radius: 14px;
            border: 1px solid rgba(18, 32, 51, .08);
            background: #fff;
        }

        .brand-title {
            font-size: 12px;
            font-weight: 900;
            line-height: 1.35;
            color: var(--ink);
            text-transform: uppercase;
        }

        .brand-sub {
            display: block;
            color: var(--ink-soft);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 4px;
            letter-spacing: .08em;
        }

        .card-chip {
            padding: 8px 10px;
            border-radius: 999px;
            background: #f9ede4;
            color: var(--brand);
            font-size: 10px;
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .photo-frame {
            width: 142px;
            height: 170px;
            margin: 8px auto 16px;
            padding: 6px;
            background: linear-gradient(180deg, #e6f7ff 0%, #ffffff 100%);
            border: 1px solid rgba(18, 32, 51, .12);
            border-radius: 14px;
            box-shadow: 0 12px 22px rgba(15, 23, 42, .08);
        }

        .photo-frame img {
            width: 100%;
            height: 100%;
            border-radius: 10px;
            object-fit: cover;
            display: block;
        }

        .front-body {
            padding: 0 24px 18px;
            text-align: center;
        }

        .member-name {
            margin: 0;
            font-size: 31px;
            line-height: 1.05;
            font-weight: 900;
            color: #111827;
        }

        .member-role {
            margin: 10px 0 0;
            font-size: 16px;
            font-weight: 800;
            color: #24364d;
        }

        .member-id-line {
            margin: 12px auto 0;
            width: 86%;
            padding-top: 12px;
            border-top: 2px solid rgba(127, 16, 16, .45);
            font-size: 17px;
            font-weight: 900;
        }

        .detail-stack {
            display: grid;
            gap: 10px;
            margin-top: 18px;
        }

        .detail-box {
            text-align: left;
            padding: 12px 14px;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid #e5edf7;
        }

        .detail-box span {
            display: block;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .12em;
            font-weight: 800;
            color: var(--ink-soft);
            margin-bottom: 5px;
        }

        .detail-box strong {
            display: block;
            font-size: 15px;
            line-height: 1.45;
            color: var(--ink);
            word-break: break-word;
        }

        .front-footer {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--brand), var(--brand-deep));
            color: #fff;
            text-align: center;
            padding: 14px 18px;
            font-size: 18px;
            font-weight: 900;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .card-back {
            background:
                linear-gradient(180deg, rgba(243, 246, 251, .92) 0%, rgba(255, 255, 255, .98) 100%);
        }

        .back-body {
            padding: 22px 22px 20px;
            display: flex;
            flex-direction: column;
            min-height: 540px;
        }

        .back-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 14px;
        }

        .back-logo img {
            width: 78px;
            height: 78px;
            object-fit: contain;
        }

        .policy-box {
            background: rgba(127, 16, 16, .04);
            border: 1px solid rgba(127, 16, 16, .08);
            border-radius: 18px;
            padding: 16px 16px 14px;
            text-align: center;
        }

        .policy-box p {
            margin: 0;
            font-size: 14px;
            line-height: 1.7;
            font-weight: 700;
            color: #27384d;
        }

        .valid-box {
            margin-top: 18px;
            text-align: center;
        }

        .valid-box strong {
            display: block;
            font-size: 15px;
            color: var(--ink);
        }

        .sign-box {
            width: 122px;
            margin: 12px auto 0;
            padding: 14px 10px 10px;
            border-radius: 10px;
            border: 1px solid rgba(18, 32, 51, .18);
            background: #fff;
        }

        .sign-box .sign-mark {
            font-family: "Brush Script MT", cursive;
            font-size: 34px;
            line-height: 1;
            color: #93aec8;
        }

        .sign-box .sign-title {
            display: block;
            margin-top: 4px;
            font-size: 12px;
            font-weight: 800;
        }

        .back-user {
            margin-top: 18px;
            display: grid;
            gap: 10px;
        }

        .back-user .detail-box {
            background: #fff;
        }

        .return-note {
            margin-top: auto;
            padding-top: 18px;
        }

        .return-note small {
            display: block;
            font-size: 12px;
            color: var(--ink-soft);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .office-box {
            background: #101010;
            color: #fff;
            border-radius: 0 0 14px 14px;
            padding: 16px 16px 18px;
            text-align: center;
        }

        .office-box strong {
            display: block;
            font-size: 18px;
            line-height: 1.35;
            margin-bottom: 6px;
        }

        .office-box p {
            margin: 0;
            font-size: 12px;
            line-height: 1.6;
            color: rgba(255, 255, 255, .8);
        }

        @media (max-width: 840px) {
            .cards-grid {
                grid-template-columns: 1fr;
            }

            .id-card {
                max-width: 360px;
            }
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .toolbar,
            .helper-note {
                display: none !important;
            }

            .cards-sheet {
                box-shadow: none;
                border: 0;
                background: #fff;
                padding: 0;
                backdrop-filter: none;
            }

            .cards-grid {
                gap: 18px;
            }

            .id-card {
                box-shadow: none;
            }

            @page {
                size: A4 portrait;
                margin: 10mm;
            }
        }
    </style>
</head>

<body>
    @php
        $fallbackPhoto = asset('assets/img/avatars/5.png');
        $photo = $cardUser->profile_photo ? asset($cardUser->profile_photo) : $fallbackPhoto;
        $logo = asset('assets/logo3.png');
        $memberId = 'FBWS-' . str_pad((string) ($cardUser->id ?? 0), 4, '0', STR_PAD_LEFT);
        $validUpto = optional($cardUser->created_at)->copy()->addYears(2)->format('M Y') ?? now()->addYears(2)->format('M Y');
        $joinedOn = $cardUser->created_at ? $cardUser->created_at->timezone(config('app.timezone'))->format('d M Y') : 'NA';
        $backUrl = $isAdminView ? route('user-management') : route('profile');
        $roleLabel = $cardUser->position ?: ucfirst((string) ($cardUser->role ?? 'Member'));
    @endphp

    <div class="page-shell">
        <div class="toolbar">
            <div class="toolbar-copy">
                <h1>E ID-Card</h1>
                <p>{{ $isAdminView ? 'Admin view for selected member card.' : 'Your personal member e-card is ready to view, print, or save.' }}</p>
            </div>

            <div class="toolbar-actions">
                <a href="{{ $backUrl }}" class="btn-back">Back</a>
                <button type="button" class="btn-download" onclick="downloadCard()">Download PDF</button>
                <button type="button" class="btn-print" onclick="window.print()">Print Card</button>
            </div>
        </div>

        <p class="helper-note">Download opens the browser print dialog where you can choose <strong>Save as PDF</strong>.</p>

        <div class="cards-wrap">
            <div class="cards-sheet" id="cardPrintableArea">
                <div class="cards-grid">
                    <section class="id-card card-front">
                        <div class="card-head">
                            <div class="brand-side">
                                <img src="{{ $logo }}" alt="FBWS Logo" class="brand-mark">
                                <div>
                                    <div class="brand-title">Farooka Brothers Welfare Society</div>
                                    <span class="brand-sub">Official Member Identity</span>
                                </div>
                            </div>
                            <div class="card-chip">Front Side</div>
                        </div>

                        <div class="photo-frame">
                            <img src="{{ $photo }}" alt="{{ $cardUser->name }}">
                        </div>

                        <div class="front-body">
                            <h2 class="member-name">{{ $cardUser->name ?? 'NA' }}</h2>
                            <p class="member-role">{{ $roleLabel }}</p>
                            <div class="member-id-line">{{ $memberId }}</div>

                            <div class="detail-stack">
                                <div class="detail-box">
                                    <span>User ID Number</span>
                                    <strong>{{ $cardUser->id ?? 'NA' }}</strong>
                                </div>

                                <div class="detail-box">
                                    <span>ID Card Number</span>
                                    <strong>{{ $cardUser->id_card ?? 'Not Assigned' }}</strong>
                                </div>

                                <div class="detail-box">
                                    <span>Phone Number</span>
                                    <strong>{{ $cardUser->phone_number ?? 'Not Provided' }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="front-footer">Member Card</div>
                    </section>

                    <section class="id-card card-back">
                        <div class="back-body">
                            <div class="back-logo">
                                <img src="{{ $logo }}" alt="FBWS Logo">
                            </div>

                            <div class="policy-box">
                                <p>
                                    The member must keep this card for society record and verification.
                                    Please produce this card whenever required.
                                </p>
                            </div>

                            <div class="valid-box">
                                <strong>Valid Upto : {{ $validUpto }}</strong>
                                <div class="sign-box">
                                    <div class="sign-mark">FBWS</div>
                                    <span class="sign-title">Authorized</span>
                                </div>
                            </div>

                            <div class="back-user">
                                <div class="detail-box">
                                    <span>Member Name</span>
                                    <strong>{{ $cardUser->name ?? 'NA' }}</strong>
                                </div>

                                <div class="detail-box">
                                    <span>Joined On</span>
                                    <strong>{{ $joinedOn }}</strong>
                                </div>

                                <div class="detail-box">
                                    <span>Address</span>
                                    <strong>{{ $cardUser->address ?? 'Address not provided' }}</strong>
                                </div>
                            </div>

                            <div class="return-note">
                                <small>Please return to the nearest FBWS office if found.</small>
                                <div class="office-box">
                                    <strong>Farooka Brothers Welfare Society</strong>
                                    <p>
                                        Member support and welfare management portal.<br>
                                        Email: {{ $cardUser->email ?? 'support@fbws.local' }}<br>
                                        Reference: {{ $memberId }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <script>
        function downloadCard() {
            window.print();
        }
    </script>
</body>

</html>
