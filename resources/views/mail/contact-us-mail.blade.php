<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 150px;
            height: auto;
        }

        .content {
            font-size: 16px;
            line-height: 1.6;
            color: #333333;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666666;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #ffffff;
            background-color: #ff6600;
            text-decoration: none;
            border-radius: 5px;
        }

        .button:hover {
            background-color: #e55d00;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ config('app.url') }}/new-logo/email-logo.png" alt="Fire & Chill Logo">
        </div>
         <div class="content">
            <p><strong>Name:</strong> {{ @$data['name'] }}</p>
            <p><strong>Email:</strong> {{ @$data['email'] }}</p>
            <p><strong>Message:</strong></p>
            <p>{{ @$data['message'] }}</p>
        </div>
        <div class="footer">
            <p>&copy; 2024 Fire & Chill. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
