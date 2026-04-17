<!DOCTYPE html>
<html lang="ur">

<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo3.png') }}" />

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Noto+Nastaliq+Urdu:wght@400;700&display=swap"
        rel="stylesheet">

    <style>
    :root {
        --slip-font: "Plus Jakarta Sans", "Noto Nastaliq Urdu", "Segoe UI", Roboto, Arial, sans-serif;
    }

    * {
        font-family: var(--slip-font);
    }

    body {
        background: #f4f6f9;
        margin: 0;
        padding: 20px;
    }

    .receipt-container {
        width: 800px;
        margin: auto;
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border: 2px solid #198754;
    }

    .header {
        text-align: center;
        border-bottom: 2px solid #198754;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .header img {
        width: 90px;
        margin-bottom: 5px;
    }

    .header h2 {
        margin: 5px 0;
        color: #198754;
    }

    .details {
        display: flex;
        justify-content: space-between;
    }

    .details div {
        width: 48%;
    }

    .details p {
        margin: 6px 0;
        font-size: 15px;
    }

    .label {
        font-weight: bold;
    }

    .message-box {
        margin-top: 25px;
        padding: 15px;
        background: #f9f9f9;
        border: 1px dashed #198754;
        border-radius: 8px;
        line-height: 1.9;
        font-size: 15px;
        white-space: pre-line;
    }

    .footer {
        text-align: center;
        margin-top: 20px;
        font-size: 13px;
        color: #777;
    }

    @media print {
        body {
            background: none;
        }

        .receipt-container {
            box-shadow: none;
            border: 2px solid #000;
        }
    }
    </style>
</head>

<body onload="window.print()">

    <div class="receipt-container">

        <!-- Header -->
        <div class="header">
            <img src="{{ asset('/assets/logo3.png') }}">
            <h2>فروکہ برادرز ویلفیئر سوسائٹی</h2>
            <strong>Payment Receipt</strong>
        </div>

        <!-- Details Section -->
        <div class="details">
            <div>
                <p><span class="label">نام:</span> {{ $payment->user->name }}</p>
                <p><span class="label">فون نمبر:</span> {{ $payment->user->phone_number }}</p>
                <p><span class="label">شناختی کارڈ:</span> {{ $payment->user->id_card }}</p>
                <p><span class="label">رقم:</span> {{ (int) $payment->amount }} روپے</p>
            </div>

            <div style="text-align:right;">
                <p><span class="label">پرنٹ تاریخ:</span> {{ date('d-m-Y') }}</p>
                <p><span class="label">مہینہ:</span> {{ $payment->month }}</p>
                <p><span class="label">رسید نمبر:</span> #{{ $payment->id }}</p>
            </div>
        </div>

        <!-- Message Box -->
        <div class="message-box">
            السلام علیکم معزز رکن سوسائٹی
            {{ $payment->user->name }}

            آپ کو مطلع کیا جاتا ہے کہ ماہ {{ $payment->month }} کا فنڈ
            ({{ (int) $payment->amount }} روپے) فروکہ برادرز ویلفیئر سوسائٹی کے اکاؤنٹ میں کامیابی سے جمع ہو چکا ہے۔

            اللہ پاک آپ کے رزق میں مزید برکتیں اور وسعتیں عطا فرمائے۔
            فروکہ برادرز ویلفیئر سوسائٹی کے ساتھ تعاون کرنے کا بہت بہت شکریہ،
            اللہ پاک آپ کو اجر عظیم عطا فرمائے۔

            آمین ثم آمین یا رب العالمین

            منجانب:
            محمد اسامہ ارشد فروکہ
            فنانس سیکرٹری
            فروکہ برادرز ویلفیئر سوسائٹی
        </div>

        <!-- Footer -->
        <div class="footer">
            <strong>Website developed by M. Saif Ali | For website development inquiries: 0327-2000339</strong>
        </div>

    </div>

</body>

</html>