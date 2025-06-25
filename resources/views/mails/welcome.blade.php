<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ env('APP_NAME') }}</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #EEECFD;
            color: #0F2358;
            padding: 20px;
            text-align: center;
        }
        .header img {
            max-width: 120px;
            margin-bottom: 10px;
        }
        .content {
            padding: 20px;
            color: #333333;
        }
        .content h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .content p {
            margin: 10px 0;
            line-height: 1.6;
        }
        .footer {
            background-color: #eeeeee;
            color: #666666;
            padding: 15px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <img src="{{ asset('pix_crm_images/pix_logo.png') }}" alt="{{ env('APP_NAME') }} Logo">
        <h2>Welcome to {{ env('APP_NAME') }}!</h2>
    </div>

    <div class="content">
        <h1>Hello, {{ $user->name }}!</h1>
        <p>Thank you for registering with <strong>{{ env('APP_NAME') }}</strong> — your trusted partner in modern construction and real estate solutions.</p>
        <p>Whether you're looking to buy, build, or invest, we're here to guide you through every step of the process with expertise and dedication.</p>
        <p>Log in to your account to explore project listings, construction updates, property details, and much more.</p>
        <p>If you need any assistance, feel free to contact our support team — we're happy to help!</p>

        <p>Welcome aboard,<br>The {{ env('APP_NAME') }} Team</p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.
    </div>
</div>

</body>
</html>
