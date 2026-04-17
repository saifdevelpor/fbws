<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Membership Card | FBWS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo3.png') }}" />
    <style>
        :root {
            --navy: #10253f;
            --navy-soft: #17385d;
            --gold: #f7b733;
            --gold-deep: #f7721e;
            --line: rgba(255, 255, 255, .16);
            --ink: #eaf2ff;
            --muted: rgba(234, 242, 255, .76);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(247, 183, 51, .18), transparent 24%),
                radial-gradient(circle at bottom right, rgba(247, 114, 30, .15), transparent 28%),
                linear-gradient(145deg, #eff4fb 0%, #dfe8f7 100%);
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 16px;
        }

        .page-shell {
            width: 100%;
            max-width: 1080px;
        }

        .toolbar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .toolbar-note {
            color: #475569;
            font-size: 14px;
            font-weight: 600;
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
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease;
        }

        .toolbar-actions a:hover,
        .toolbar-actions button:hover {
            transform: translateY(-2px);
        }

        .btn-back {
            background: #fff;
            color: #10253f;
            box-shadow: 0 12px 28px rgba(15, 23, 42, .08);
        }

        .btn-print {
            background: linear-gradient(135deg, var(--gold-deep), var(--gold));
            color: #fff;
            box-shadow: 0 16px 36px rgba(247, 114, 30, .25);
        }

        .card-stage {
            display: flex;
            justify-content: center;
        }

        .member-card {
            width: 100%;
            max-width: 860px;
            min-height: 520px;
            background: linear-gradient(145deg, var(--navy) 0%, var(--navy-soft) 56%, #24518a 100%);
            border-radius: 32px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 30px 70px rgba(15, 23, 42, .22);
        }

        .member-card::before,
        .member-card::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .member-card::before {
            width: 280px;
            height: 280px;
            right: -120px;
            top: -120px;
            background: radial-gradient(circle, rgba(247, 183, 51, .32), transparent 68%);
        }

        .member-card::after {
            width: 260px;
            height: 260px;
            left: -110px;
            bottom: -130px;
            background: radial-gradient(circle, rgba(255, 255, 255, .12), transparent 72%);
        }

        .card-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 26px 30px 0;
            position: relative;
            z-index: 2;
        }

        .brand-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-logo {
            width: 78px;
            height: 78px;
            object-fit: contain;
            border-radius: 50%;
            background: rgba(255, 255, 255, .95);
            padding: 6px;
            box-shadow: 0 12px 28px rgba(15, 23, 42, .16);
        }

        .brand-text small {
            display: block;
            color: rgba(255, 255, 255, .72);
            letter-spacing: .16em;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .brand-text h1 {
            margin: 0;
            color: #fff;
            font-size: 26px;
            line-height: 1.1;
            max-width: 360px;
        }

        .member-badge {
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .16);
            color: #fff;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .card-body {
            position: relative;
            z-index: 2;
            padding: 26px 30px 30px;
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
            gap: 26px;
            align-items: stretch;
        }

        .profile-panel {
            background: rgba(255, 255, 255, .08);
            border: 1px solid var(--line);
            border-radius: 28px;
            padding: 22px 22px 18px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .profile-photo {
            width: 176px;
            height: 176px;
            border-radius: 28px;
            object-fit: cover;
            background: #fff;
            padding: 6px;
            box-shadow: 0 16px 36px rgba(15, 23, 42, .18);
        }

        .profile-name {
            margin: 18px 0 6px;
            color: #fff;
            font-size: 28px;
            font-weight: 900;
            line-height: 1.15;
        }

        .profile-role {
            color: #ffe9b8;
            font-size: 14px;
            font-weight: 700;
        }

        .id-chip {
            margin-top: 16px;
            width: 100%;
            padding: 12px 14px;
            border-radius: 18px;
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .14);
            color: #fff;
        }

        .id-chip span {
            display: block;
            font-size: 11px;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 4px;
        }

        .id-chip strong {
            font-size: 18px;
            letter-spacing: .04em;
        }

        .details-panel {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 20px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .detail-box {
            background: rgba(255, 255, 255, .08);
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 16px 18px;
            min-height: 94px;
        }

        .detail-box.wide {
            grid-column: 1 / -1;
        }

        .detail-label {
            font-size: 11px;
            color: var(--muted);
            letter-spacing: .12em;
            text-transform: uppercase;
            margin-bottom: 9px;
            display: block;
        }

        .detail-value {
            color: var(--ink);
            font-size: 18px;
            font-weight: 800;
            line-height: 1.45;
            word-break: break-word;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 18px;
            padding-top: 6px;
        }

        .footer-note {
            color: rgba(255, 255, 255, .74);
            font-size: 12px;
            line-height: 1.6;
            max-width: 420px;
        }

        .footer-sign {
            text-align: right;
            color: #fff;
        }

        .footer-sign strong {
            display: block;
            font-size: 15px;
        }

        .footer-sign span {
            display: block;
            font-size: 12px;
            color: rgba(255, 255, 255, .68);
            margin-top: 3px;
        }

        @media (max-width: 900px) {
            .card-body {
                grid-template-columns: 1fr;
            }

            .profile-panel {
                align-items: center;
            }
        }

        @media (max-width: 640px) {
            body {
                padding: 16px 10px 24px;
            }

            .card-topbar,
            .card-body {
                padding-left: 18px;
                padding-right: 18px;
            }

            .brand-text h1 {
                font-size: 21px;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }

            .profile-photo {
                width: 150px;
                height: 150px;
            }

            .profile-name {
                font-size: 24px;
            }

            .card-footer {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .toolbar {
                display: none !important;
            }

            .page-shell {
                max-width: none;
            }

            .member-card {
                max-width: none;
                width: 100%;
                box-shadow: none;
                border-radius: 0;
                min-height: auto;
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
        $joinedOn = $cardUser->created_at ? $cardUser->created_at->timezone(config('app.timezone'))->format('d M Y') : 'NA';
        $backUrl = $isAdminView ? route('user-management') : route('profile');
    @endphp

    <div class="page-shell">
        <div class="toolbar">
            <div class="toolbar-note">
                {{ $isAdminView ? 'Admin can print this member card for any user.' : 'You can print or save your membership card as PDF.' }}
            </div>
            <div class="toolbar-actions">
                <a href="{{ $backUrl }}" class="btn-back">Back</a>
                <button type="button" class="btn-print" onclick="window.print()">Download / Print Card</button>
            </div>
        </div>

        <div class="card-stage">
            <section class="member-card">
                <div class="card-topbar">
                    <div class="brand-wrap">
                        <img src="{{ asset('assets/logo3.png') }}" alt="FBWS Logo" class="brand-logo">
                        <div class="brand-text">
                            <small>Welfare Society</small>
                            <h1>Farooka Brothers Welfare Society</h1>
                        </div>
                    </div>
                    <div class="member-badge">Membership Card</div>
                </div>

                <div class="card-body">
                    <div class="profile-panel">
                        <img src="{{ $photo }}" alt="{{ $cardUser->name }}" class="profile-photo">
                        <h2 class="profile-name">{{ $cardUser->name ?? 'NA' }}</h2>
                        <div class="profile-role">{{ $cardUser->position ?? ucfirst($cardUser->role ?? 'Member') }}</div>

                        <div class="id-chip">
                            <span>ID Card Number</span>
                            <strong>{{ $cardUser->id_card ?? 'Not Assigned' }}</strong>
                        </div>
                    </div>

                    <div class="details-panel">
                        <div class="details-grid">
                            <div class="detail-box">
                                <span class="detail-label">Member Name</span>
                                <div class="detail-value">{{ $cardUser->name ?? 'NA' }}</div>
                            </div>

                            <div class="detail-box">
                                <span class="detail-label">Phone Number</span>
                                <div class="detail-value">{{ $cardUser->phone_number ?? 'NA' }}</div>
                            </div>

                            <div class="detail-box">
                                <span class="detail-label">Position</span>
                                <div class="detail-value">{{ $cardUser->position ?? ucfirst($cardUser->role ?? 'Member') }}</div>
                            </div>

                            <div class="detail-box">
                                <span class="detail-label">Joined On</span>
                                <div class="detail-value">{{ $joinedOn }}</div>
                            </div>

                            <div class="detail-box wide">
                                <span class="detail-label">Address</span>
                                <div class="detail-value">{{ $cardUser->address ?? 'Address not provided' }}</div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="footer-note">
                                This card identifies the member of Farooka Brothers Welfare Society. Please keep this
                                card safe for society participation, welfare support, and record verification.
                            </div>

                            <div class="footer-sign">
                                <strong>FBWS Official Record</strong>
                                <span>{{ $cardUser->email ?? 'Member Account' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>

</html>
