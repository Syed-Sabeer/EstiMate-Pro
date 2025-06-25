<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
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
        .details {
            margin: 20px 0;
            padding: 15px;
            background-color: #EEECFD;
            border: 1px solid #eeeeee;
            border-radius: 4px;
        }
        .details h3 {
            font-size: 18px;
            margin-bottom: 8px;
        }
        .footer {
            background-color: #eeeeee;
            color: #666666;
            padding: 15px;
            text-align: center;
            font-size: 12px;
        }
        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                width: 90%;
            }
            .header img {
                max-width: 100px;
            }
            .content h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <img src="{{ asset('pix_crm_images/pix_logo.png') }}" alt="Company Logo">
        <h2>Order Confirmation</h2>
    </div>

    <div class="content">
        <h1>Thank you for your purchase!</h1>
        <p>Dear {{ $payment->first_name }} {{ $payment->last_name }},</p>
        <p>We appreciate your business and are pleased to confirm your order. Here are the details:</p>

        <div class="details">
            <p><strong>Order ID:</strong> #000{{ $payment->id }}</p>
            <p><strong>Amount Paid:</strong> ${{ number_format($payment->amount, 2) }}</p>
            <p><strong>Payment Method:</strong> {{ $payment->payment_method }}</p>
            <p><strong>Card Number:</strong> {{ $payment->card_number }}</p>
        </div>

        <div class="details">
            <h3>Plan Details</h3>
            <p><strong>Plan Name:</strong> {{ $plan->name }}</p>
            <p><strong>Plan Price:</strong> ${{ number_format($plan->price, 2) }}</p>
            <p><strong>Users Limit:</strong> {{ $plan->user_limit }}</p>
            <p><strong>Storage Space:</strong> {{ number_format($plan->storage_limit / (1024 ** 3), 2) }} GB</p>
        </div>

        <p>Your payment has been processed successfully, and your order is now complete.</p>
        <p>Thank you for choosing us! If you have any questions, feel free to contact our support team.</p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Pix CRM. All rights reserved.
    </div>
</div>

</body>
</html>

