@extends('home')

@section('title')
<title>E ID-Card | FBWS</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;700&display=swap" rel="stylesheet">
@endsection

@section('content')
@php
    $fallbackPhoto = asset('assets/img/avatars/5.png');
    $photo = $cardUser->profile_photo ? asset($cardUser->profile_photo) : $fallbackPhoto;
    $logo = asset('assets/logo3.png');
    $memberId = 'FBWS-' . str_pad((string) ($cardUser->id ?? 0), 4, '0', STR_PAD_LEFT);
    $validUpto = optional($cardUser->created_at)->copy()->addYears(2)->format('M Y') ?? now()->addYears(2)->format('M Y');
    $joinedOn = $cardUser->created_at ? $cardUser->created_at->timezone(config('app.timezone'))->format('d M Y') : 'NA';
    $backUrl = $isAdminView ? route('user-management') : route('profile');
    $roleLabel = $cardUser->position ?: ucfirst((string) ($cardUser->role ?? 'Member'));
    $actualRole = ucfirst((string) ($cardUser->role ?? 'Member'));
    $positionLabel = $cardUser->position ?: 'Member';
    $phoneNumber = $cardUser->phone_number ?: 'Not Provided';
    $verificationUrl = route('account.membership-card.verify', \Illuminate\Support\Facades\Crypt::encryptString((string) $cardUser->id));
    $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&margin=0&data=' . urlencode($verificationUrl);
    $downloadPayload = [
        'name' => $cardUser->name ?? 'NA',
        'role' => $actualRole,
        'position' => $positionLabel,
        'memberId' => $memberId,
        'userId' => (string) ($cardUser->id ?? 'NA'),
        'idCard' => $cardUser->id_card ?? 'Not Assigned',
        'phone' => $phoneNumber,
        'joinedOn' => $joinedOn,
        'address' => $cardUser->address ?? 'Address not provided',
        'validUpto' => $validUpto,
        'email' => 'farookabrotherswelfearsociety@gmail.com',
        'photo' => $photo,
        'logo' => $logo,
        'qr' => $qrImageUrl,
    ];
@endphp

<style>
    .eid-page { max-width: 1320px; margin: 0 auto; }
    .eid-hero {
        border-radius: 22px; padding: 22px 24px; margin-bottom: 18px;
        background: linear-gradient(135deg, #fff 0%, #fff7f1 45%, #edf5ff 100%);
        border: 1px solid rgba(247,114,30,.14); box-shadow: 0 14px 32px rgba(15,23,42,.07);
    }
    .eid-hero-top { display:flex; justify-content:space-between; align-items:flex-start; gap:16px; flex-wrap:wrap; }
    .eid-hero h1 { margin:0; font-size:2rem; font-weight:900; color:#122033; letter-spacing:-.03em; }
    .eid-hero p, .eid-note { margin:8px 0 0; color:#607287; font-size:14px; }
    .eid-note { font-size:12px; }
    .eid-toolbar { display:flex; flex-wrap:wrap; gap:10px; }
    .eid-toolbar a, .eid-toolbar button {
        border:0; border-radius:999px; padding:10px 16px; font-size:14px; font-weight:800;
        text-decoration:none; cursor:pointer; transition:transform .2s ease, box-shadow .2s ease;
    }
    .eid-toolbar a:hover, .eid-toolbar button:hover { transform:translateY(-2px); }
    .eid-btn-back { color:#122033; background:#fff; box-shadow:0 8px 20px rgba(15,23,42,.08); }
    .eid-btn-download { color:#fff; background:linear-gradient(135deg,#2b5c88,#173656); box-shadow:0 12px 24px rgba(23,54,86,.2); }
    .eid-btn-print { color:#fff; background:linear-gradient(135deg,#7f1010,#5d0d0d); box-shadow:0 12px 24px rgba(127,16,16,.18); }
    .eid-stage {
        border-radius:24px; padding:18px; background:linear-gradient(180deg,rgba(248,250,252,.98),rgba(255,255,255,.98));
        border:1px solid #e6edf5; box-shadow:0 18px 40px rgba(15,23,42,.06);
    }
    .eid-grid { display:grid; grid-template-columns:repeat(2,minmax(280px,330px)); gap:14px; justify-content:center; }
    .eid-card {
        width:100%; min-height:452px; background:#fff; border:1px solid #d9e3ee; border-radius:20px;
        overflow:hidden; box-shadow:0 14px 28px rgba(15,23,42,.08); position:relative;
    }
    .eid-card::before { content:""; position:absolute; inset:10px; border:1px solid rgba(18,32,51,.08); border-radius:14px; pointer-events:none; }
    .eid-front-head { display:flex; justify-content:space-between; gap:10px; padding:18px 18px 8px; align-items:flex-start; }
    .eid-brand { display:flex; gap:10px; align-items:center; }
    .eid-brand img { width:48px; height:48px; object-fit:contain; border-radius:12px; border:1px solid rgba(18,32,51,.1); background:#fff; padding:4px; }
    .eid-brand-title { font-size:10px; line-height:1.4; font-weight:900; text-transform:uppercase; color:#122033; }
    .eid-brand-sub { display:block; margin-top:4px; font-size:9px; letter-spacing:.11em; color:#607287; }
    .eid-chip { padding:7px 10px; border-radius:999px; background:#f9eee6; color:#7f1010; font-size:10px; font-weight:900; letter-spacing:.12em; }
    .eid-photo { width:110px; height:128px; margin:8px auto 12px; padding:5px; border-radius:14px; border:1px solid rgba(18,32,51,.12); background:linear-gradient(180deg,#e6f7ff 0%,#fff 100%); }
    .eid-photo img { width:100%; height:100%; object-fit:cover; border-radius:10px; }
    .eid-front-body { padding:0 16px 64px; text-align:center; }
    .eid-name { margin:0; font-size:21px; line-height:1.12; font-weight:900; color:#101827; }
    .eid-role { margin:6px 0 0; color:#334a65; font-size:14px; font-weight:800; }
    .eid-member-id { margin:8px auto 10px; width:82%; padding-top:8px; border-top:2px solid rgba(127,16,16,.35); font-size:14px; font-weight:900; }
    .eid-mini-grid, .eid-back-grid { display:grid; gap:8px; }
    .eid-box { text-align:left; padding:9px 11px; border-radius:14px; background:#f8fbff; border:1px solid #e4edf7; }
    .eid-box span { display:block; margin-bottom:4px; font-size:9px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; color:#607287; }
    .eid-box strong { display:block; font-size:13px; color:#122033; line-height:1.4; word-break:break-word; }
    .eid-qr-wrap {
        margin: 10px auto 0;
        width: 98px;
        text-align: center;
    }
    .eid-qr-wrap img {
        width: 98px;
        height: 98px;
        border-radius: 12px;
        border: 1px solid #dbe5f0;
        background: #fff;
        padding: 4px;
    }
    .eid-qr-wrap span {
        display: block;
        margin-top: 5px;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .12em;
        color: #607287;
        text-transform: uppercase;
    }
    .eid-front-footer {
        position:absolute; left:0; right:0; bottom:0; padding:14px 16px; text-align:center; color:#fff;
        background:linear-gradient(135deg,#7f1010,#5d0d0d); font-size:16px; font-weight:900; letter-spacing:.08em; text-transform:uppercase;
    }
    .eid-back { background:linear-gradient(180deg,#f7f9fc 0%,#fff 100%); }
    .eid-back-body { min-height:452px; padding:14px 14px 12px; display:flex; flex-direction:column; }
    .eid-back-logo { text-align:center; margin-bottom:10px; }
    .eid-back-logo img { width:58px; height:58px; object-fit:contain; }
    .eid-policy {
        padding:14px; border-radius:18px; border:1px solid rgba(127,16,16,.08); background:rgba(127,16,16,.04);
        text-align:center; font-size:12px; font-weight:700; line-height:1.7; color:#2f4258;
    }
    .eid-valid { margin-top:12px; text-align:center; }
    .eid-valid strong { display:block; font-size:13px; }
    .eid-sign { width:106px; margin:10px auto 0; padding:10px 8px 8px; border-radius:10px; border:1px solid rgba(18,32,51,.16); background:#fff; }
    .eid-sign-mark { font-family:"Brush Script MT",cursive; font-size:27px; line-height:1; color:#93aec8; }
    .eid-sign small { display:block; margin-top:2px; font-size:11px; font-weight:800; }
    .eid-return { margin-top:auto; padding-top:12px; }
    .eid-return small { display:block; margin-bottom:6px; font-size:11px; color:#607287; font-weight:700; }
    .eid-office { padding:14px; border-radius:0 0 14px 14px; background:#111; color:#fff; text-align:center; }
    .eid-office strong { display:block; font-size:13px; line-height:1.45; margin-bottom:4px; }
    .eid-office p { margin:0; font-size:11px; line-height:1.55; color:rgba(255,255,255,.82); }
    .eid-urdu {
        font-family: "Noto Nastaliq Urdu", "Segoe UI", serif;
        direction: rtl;
    }
    @media (max-width: 991px) { .eid-grid { grid-template-columns:1fr; } }
    @media print {
        body * {
            visibility: hidden !important;
        }
        .eid-stage, .eid-stage * {
            visibility: visible !important;
        }
        .layout-navbar, .layout-menu, .footer, .eid-hero { display:none !important; }
        .content-wrapper, .content-wrapper>.container-xxl, .eid-page, .eid-stage {
            padding:0 !important; margin:0 !important; max-width:none !important; box-shadow:none !important; border:0 !important; background:#fff !important;
        }
        .eid-stage {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 690px !important;
            margin: 0 auto !important;
        }
        .eid-grid {
            grid-template-columns: repeat(2, 338px) !important;
            gap: 12px !important;
            align-items: start !important;
        }
        .eid-card {
            min-height: 430px !important;
            box-shadow:none;
            break-inside: avoid !important;
            page-break-inside: avoid !important;
        }
        .eid-photo {
            width: 96px !important;
            height: 112px !important;
            margin-bottom: 8px !important;
        }
        .eid-name {
            font-size: 18px !important;
        }
        .eid-role,
        .eid-member-id,
        .eid-box strong,
        .eid-policy,
        .eid-valid strong,
        .eid-office p {
            font-size: 12px !important;
        }
        .eid-qr-wrap img {
            width: 72px !important;
            height: 72px !important;
        }
        @page { size: A4 landscape; margin: 4mm; }
    }
</style>

<div class="eid-page">
    <div class="eid-hero">
        <div class="eid-hero-top">
            <div>
                <h1>E ID-Card</h1>
                <p>{{ $isAdminView ? 'Selected member card preview for admin.' : 'Your member card is now inside the portal layout.' }}</p>
                <div class="eid-note">Number field ko compact card mein visible kar diya gaya hai.</div>
            </div>
            <div class="eid-toolbar">
                <a href="{{ $backUrl }}" class="eid-btn-back">Back</a>
                <button type="button" class="eid-btn-download" onclick="downloadCardSheet()">Download</button>
                <button type="button" class="eid-btn-print" onclick="window.print()">Print Card</button>
            </div>
        </div>
    </div>

    <div class="eid-stage" id="eidCardSheet">
        <div class="eid-grid">
            <section class="eid-card">
                <div class="eid-front-head">
                    <div class="eid-brand">
                        <img src="{{ $logo }}" alt="FBWS Logo">
                        <div class="eid-brand-title">
                            Farooka Brothers Welfare Society
                            <span class="eid-brand-sub">Official Member Identity</span>
                        </div>
                    </div>
                    <div class="eid-chip">Front Side</div>
                </div>
                <div class="eid-photo"><img src="{{ $photo }}" alt="{{ $cardUser->name }}"></div>
                <div class="eid-front-body">
                    <h2 class="eid-name eid-urdu">{{ $cardUser->name ?? 'NA' }}</h2>
                    <p class="eid-role">{{ $roleLabel }}</p>
                    <div class="eid-member-id">{{ $memberId }}</div>
                    <div class="eid-mini-grid">
                        <div class="eid-box"><span>Role</span><strong>{{ $actualRole }}</strong></div>
                        <div class="eid-box"><span>User ID Number</span><strong>{{ $cardUser->id ?? 'NA' }}</strong></div>
                        <div class="eid-box"><span>ID Card Number</span><strong>{{ $cardUser->id_card ?? 'Not Assigned' }}</strong></div>
                        <div class="eid-box"><span>Phone Number</span><strong>{{ $phoneNumber }}</strong></div>
                    </div>
                    <div class="eid-qr-wrap">
                        <img src="{{ $qrImageUrl }}" alt="Verification QR">
                        <span>Scan to Verify</span>
                    </div>
                </div>
                <div class="eid-front-footer">Member Card</div>
            </section>

            <section class="eid-card eid-back">
                <div class="eid-back-body">
                    <div class="eid-back-logo"><img src="{{ $logo }}" alt="FBWS Logo"></div>
                    <div class="eid-policy">
                        The member must keep this card for society record and verification.
                        Please produce this card whenever required.
                    </div>
                    <div class="eid-valid">
                        <strong>Valid Upto : {{ $validUpto }}</strong>
                        <div class="eid-sign">
                            <div class="eid-sign-mark">FBWS</div>
                            <small>Authorized</small>
                        </div>
                    </div>
                    <div class="eid-back-grid">
                        <div class="eid-box"><span>Position</span><strong>{{ $positionLabel }}</strong></div>
                        <div class="eid-box"><span>Joined On</span><strong>{{ $joinedOn }}</strong></div>
                        <div class="eid-box"><span>Phone Number</span><strong>{{ $phoneNumber }}</strong></div>
                        <div class="eid-box"><span>Address</span><strong class="eid-urdu">{{ $cardUser->address ?? 'Address not provided' }}</strong></div>
                    </div>
                    <div class="eid-return">
                        <small>Please return to the nearest FBWS office if found.</small>
                        <div class="eid-office">
                            <strong>Farooka Brothers Welfare Society</strong>
                            <p>Email: farookabrotherswelfearsociety@gmail.com<br>Reference: {{ $memberId }}</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    const eidCardData = @json($downloadPayload);

    function roundRect(ctx, x, y, width, height, radius, fillStyle, strokeStyle = null, lineWidth = 1) {
        ctx.beginPath();
        ctx.moveTo(x + radius, y);
        ctx.arcTo(x + width, y, x + width, y + height, radius);
        ctx.arcTo(x + width, y + height, x, y + height, radius);
        ctx.arcTo(x, y + height, x, y, radius);
        ctx.arcTo(x, y, x + width, y, radius);
        ctx.closePath();
        if (fillStyle) {
            ctx.fillStyle = fillStyle;
            ctx.fill();
        }
        if (strokeStyle) {
            ctx.lineWidth = lineWidth;
            ctx.strokeStyle = strokeStyle;
            ctx.stroke();
        }
    }

    function drawWrappedText(ctx, text, x, y, maxWidth, lineHeight, maxLines = 3) {
        const words = String(text || '').split(' ');
        let line = '';
        let lineCount = 0;

        for (let n = 0; n < words.length; n++) {
            const testLine = line + words[n] + ' ';
            if (ctx.measureText(testLine).width > maxWidth && n > 0) {
                ctx.fillText(line.trim(), x, y);
                line = words[n] + ' ';
                y += lineHeight;
                lineCount++;
                if (lineCount >= maxLines - 1) {
                    break;
                }
            } else {
                line = testLine;
            }
        }

        ctx.fillText(line.trim(), x, y);
    }

    function loadImage(src) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = () => resolve(img);
            img.onerror = reject;
            img.src = src;
        });
    }

    function isUrduText(text) {
        return /[\u0600-\u06FF]/.test(String(text || ''));
    }

    function setCanvasFont(ctx, weight, size, text, align = 'start') {
        const family = isUrduText(text)
            ? '"Noto Nastaliq Urdu", "Segoe UI", serif'
            : '"Segoe UI", Arial, sans-serif';
        ctx.font = `${weight} ${size}px ${family}`;
        ctx.textAlign = align;
    }

    async function downloadCardSheet() {
        try {
            if (document.fonts && document.fonts.load) {
                await Promise.all([
                    document.fonts.load('700 24px "Noto Nastaliq Urdu"'),
                    document.fonts.load('900 32px "Noto Nastaliq Urdu"')
                ]);
            }

            const [photoImg, logoImg, qrImg] = await Promise.all([
                loadImage(eidCardData.photo),
                loadImage(eidCardData.logo),
                loadImage(eidCardData.qr),
            ]);

            const canvas = document.createElement('canvas');
            canvas.width = 1600;
            canvas.height = 900;
            const ctx = canvas.getContext('2d');

            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            const drawCardBase = (x, y, w, h) => {
                roundRect(ctx, x, y, w, h, 26, '#ffffff', '#d9e3ee', 2);
                roundRect(ctx, x + 12, y + 12, w - 24, h - 24, 18, null, 'rgba(18,32,51,0.08)', 2);
            };

            const drawInfoBox = (x, y, w, label, value) => {
                roundRect(ctx, x, y, w, 62, 16, '#f8fbff', '#e4edf7', 2);
                ctx.fillStyle = '#607287';
                setCanvasFont(ctx, '700', 13, label);
                ctx.fillText(label.toUpperCase(), x + 14, y + 22);
                ctx.fillStyle = '#122033';
                setCanvasFont(ctx, '700', 18, value);
                drawWrappedText(ctx, value, x + 14, y + 47, w - 28, 18, 2);
            };

            const frontX = 170, frontY = 70, cardW = 540, cardH = 720;
            const backX = 760, backY = 70;

            drawCardBase(frontX, frontY, cardW, cardH);
            drawCardBase(backX, backY, cardW, cardH);

            ctx.drawImage(logoImg, frontX + 24, frontY + 22, 48, 48);
            ctx.fillStyle = '#122033';
            setCanvasFont(ctx, '900', 17, 'FAROOKA BROTHERS WELFARE');
            ctx.fillText('FAROOKA BROTHERS WELFARE', frontX + 86, frontY + 34);
            ctx.fillText('SOCIETY', frontX + 86, frontY + 58);
            ctx.fillStyle = '#607287';
            setCanvasFont(ctx, '700', 13, 'OFFICIAL MEMBER IDENTITY');
            ctx.fillText('OFFICIAL MEMBER IDENTITY', frontX + 86, frontY + 88);
            ctx.fillStyle = '#7f1010';
            setCanvasFont(ctx, '900', 14, 'Front Side');
            ctx.fillText('Front Side', frontX + 395, frontY + 52);

            roundRect(ctx, frontX + 180, frontY + 110, 180, 210, 20, '#ffffff', 'rgba(18,32,51,0.12)', 2);
            ctx.drawImage(photoImg, frontX + 190, frontY + 120, 160, 190);

            ctx.fillStyle = '#101827';
            setCanvasFont(ctx, '900', 34, eidCardData.name, 'center');
            drawWrappedText(ctx, eidCardData.name, frontX + cardW / 2, frontY + 370, 340, 38, 2);
            ctx.fillStyle = '#334a65';
            setCanvasFont(ctx, '800', 28, eidCardData.role, 'center');
            ctx.fillText(eidCardData.role, frontX + cardW / 2, frontY + 435);
            ctx.fillStyle = '#122033';
            setCanvasFont(ctx, '900', 22, eidCardData.memberId, 'center');
            ctx.fillText(eidCardData.memberId, frontX + cardW / 2, frontY + 495);
            ctx.textAlign = 'start';
            ctx.strokeStyle = 'rgba(127,16,16,0.35)';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(frontX + 80, frontY + 455);
            ctx.lineTo(frontX + cardW - 80, frontY + 455);
            ctx.stroke();

            drawInfoBox(frontX + 24, frontY + 520, cardW - 48, 'Role', eidCardData.role);
            drawInfoBox(frontX + 24, frontY + 592, 238, 'User ID Number', eidCardData.userId);
            drawInfoBox(frontX + 278, frontY + 592, 238, 'ID Card Number', eidCardData.idCard);
            roundRect(ctx, frontX + 206, frontY + 662, 128, 128, 16, '#ffffff', '#dbe5f0', 2);
            ctx.drawImage(qrImg, frontX + 220, frontY + 676, 100, 100);
            ctx.fillStyle = '#607287';
            setCanvasFont(ctx, '700', 12, 'Scan to Verify', 'center');
            ctx.fillText('Scan to Verify', frontX + 270, frontY + 807);

            roundRect(ctx, frontX, frontY + cardH - 58, cardW, 58, 0, '#7f1010');
            ctx.fillStyle = '#ffffff';
            ctx.font = '900 20px Segoe UI';
            ctx.textAlign = 'center';
            ctx.fillText('MEMBER CARD', frontX + cardW / 2, frontY + cardH - 22);
            ctx.textAlign = 'start';

            ctx.drawImage(logoImg, backX + 241, backY + 24, 58, 58);
            roundRect(ctx, backX + 28, backY + 110, cardW - 56, 120, 24, 'rgba(127,16,16,0.04)', 'rgba(127,16,16,0.08)', 2);
            ctx.fillStyle = '#2f4258';
            setCanvasFont(ctx, '700', 18, 'The member must keep this card for society record and verification.', 'center');
            drawWrappedText(ctx, 'The member must keep this card for society record and verification. Please produce this card whenever required.', backX + cardW / 2, backY + 155, cardW - 110, 34, 3);
            ctx.fillStyle = '#122033';
            setCanvasFont(ctx, '800', 18, `Valid Upto : ${eidCardData.validUpto}`, 'center');
            ctx.fillText(`Valid Upto : ${eidCardData.validUpto}`, backX + cardW / 2, backY + 275);
            ctx.textAlign = 'start';

            roundRect(ctx, backX + 200, backY + 305, 140, 86, 14, '#ffffff', 'rgba(18,32,51,0.16)', 2);
            ctx.fillStyle = '#93aec8';
            setCanvasFont(ctx, '900', 34, 'FBWS', 'center');
            ctx.fillText('FBWS', backX + 270, backY + 356);
            ctx.fillStyle = '#122033';
            setCanvasFont(ctx, '800', 16, 'Authorized', 'center');
            ctx.fillText('Authorized', backX + 270, backY + 382);
            ctx.textAlign = 'start';

            drawInfoBox(backX + 24, backY + 410, cardW - 48, 'Position', eidCardData.position);
            drawInfoBox(backX + 24, backY + 482, cardW - 48, 'Joined On', eidCardData.joinedOn);
            drawInfoBox(backX + 24, backY + 554, cardW - 48, 'Phone Number', eidCardData.phone);
            drawInfoBox(backX + 24, backY + 626, cardW - 48, 'Address', eidCardData.address);

            ctx.fillStyle = '#607287';
            setCanvasFont(ctx, '700', 14, 'Please return to the nearest FBWS office if found.');
            ctx.fillText('Please return to the nearest FBWS office if found.', backX + 24, backY + 710);
            roundRect(ctx, backX + 24, backY + 726, cardW - 48, 46, 0, '#111111');
            ctx.fillStyle = '#ffffff';
            setCanvasFont(ctx, '800', 15, 'Farooka Brothers Welfare Society', 'center');
            ctx.fillText('Farooka Brothers Welfare Society', backX + cardW / 2, backY + 755);
            ctx.textAlign = 'start';

            const link = document.createElement('a');
            link.href = canvas.toDataURL('image/png');
            link.download = 'fbws-e-id-card.png';
            document.body.appendChild(link);
            link.click();
            link.remove();
        } catch (error) {
            window.print();
        }
    }
</script>
@endsection
