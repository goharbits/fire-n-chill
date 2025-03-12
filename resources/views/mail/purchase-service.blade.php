<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Service Details</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
       <div class="header">
            <img src="{{ config('app.url') }}/new-logo/email-logo.png" alt="Fire & Chill Logo">
        </div>
        <div class="content">
            <h1>Hello, {{ $data['full_name'] }}!</h1>
            <p>Thank you for purchasing the service. Here are the details:</p>
            @if (!empty($data['serviceDetails']))
                <table>

                    <tr>
                        <th>Service Name</th>
                        <td>{{ $data['serviceDetails']['name'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Service Count</th>
                        <td>{{ $data['serviceDetails']['count'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td>${{ $data['serviceDetails']['price'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $data['serviceDetails']['description'] ?? 'N/A' }}</td>
                    </tr>
                     <tr>
                        <th>Status</th>
                        <td>Active</td>
                    </tr>
                </table>
            @else
                <p>No contract details available.</p>
            @endif

            <p>If you have any questions or need further assistance, feel free to contact us.</p>
                <p>Best regards,<br>The Fire & Chill Team</p>
        </div>
        <div class="footer">
            <p>&copy; 2024 Fire & Chill. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
