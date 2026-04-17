<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to FBWS</title>
    <style>
        /* Add some basic styling to center the logo and format the email */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
        }

        .email-container {
            width: 100%;
            max-width: 600px;
            background-color: #ffffff;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            width: 200px;
            margin: 20px 0;
        }

        h1,
        h3 {
            color: #333;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }

        .footer a {
            color: #4A90E2;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Logo at the top -->
        <img src="{{ asset('assets/logo3.png') }}" alt="FBWS Logo" class="logo">

        <h1>Hello {{ $user->name }},</h1>
        <p>Welcome to FBWS! We're excited to have you join us.</p>

        <h3>Your account details:</h3>
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Password :</strong>{{ $user->password }}</p>

        <p>Thank you for joining us! We hope you have a great experience with FBWS.</p>

        <!-- Footer with website link -->
        <div class="footer">
            <p>Please Login user account at: <a href="https://www.fbws.com" target="_blank">www.fbws.com</a></p>
        </div>
    </div>
</body>

</html>
