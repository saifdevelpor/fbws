<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>New Membership Request - FBWS</title>
</head>

<body style="margin:0; padding:0; background:#f4f6fb; font-family: Arial, sans-serif;">

    <div
        style="max-width:600px; margin:30px auto; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.08);">

        <!-- Logo Section -->
        <div style="background:#007bff; padding:25px; text-align:center;">
            <img src="{{ asset('website/assets/img/logo/loder.png') }}" alt="FBWS Logo" style="max-width:140px;">
        </div>

        <!-- Content -->
        <div style="padding:30px;">

            <h2 style="margin-top:0; color:#111;">New Membership Request</h2>

            <p style="color:#666; margin-bottom:25px;">
                Someone has submitted the "Become a Part" form.
            </p>

            <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse:collapse;">
                <tr style="background:#f8f9fc;">
                    <td><strong>Name</strong></td>
                    <td>{{ $data['name'] ?? '-' }}</td>
                </tr>

                <tr style="background:#f8f9fc;">
                    <td><strong>Father Name</strong></td>
                    <td>{{ $data['father_name'] ?? '-' }}</td>
                </tr>

                <tr style="background:#f8f9fc;">
                    <td><strong>ID Card</strong></td>
                    <td>{{ $data['id_card'] ?? '-' }}</td>
                </tr>

                <tr>
                    <td><strong>Email</strong></td>
                    <td>{{ $data['email'] ?? '-' }}</td>
                </tr>

                <tr style="background:#f8f9fc;">
                    <td><strong>Phone</strong></td>
                    <td>{{ $data['phone'] ?? '-' }}</td>
                </tr>

                <tr>
                    <td><strong>City</strong></td>
                    <td>{{ $data['adddress'] ?? '-' }}</td>
                </tr>

                <tr style="background:#f8f9fc;">
                    <td><strong>Message</strong></td>
                    <td>{{ $data['message'] ?? '-' }}</td>
                </tr>
            </table>

        </div>

        <!-- Footer -->
        <div style="background:#f1f3fa; padding:15px; text-align:center; font-size:12px; color:#777;">
            This email was sent from FBWS Website.
        </div>

    </div>

</body>

</html>
