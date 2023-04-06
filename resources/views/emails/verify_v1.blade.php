<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email Address</title>
    <style>
        /* Global styles */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.5;
            color: #333;
        }

        /* Header styles */
        .header {
            background-color: #f2f2f2;
            padding: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        /* Body styles */
        .body {
            padding: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        /* Footer styles */
        .footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        .footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>MyApp</h1>
    </div>
    <div class="body">
        <p>Thanks for registering!</p>
        <p>Please click the button below to verify your email address:</p>
        <a class="button" href="{{ url('email/verify/' . $user->email_verification_token) }}">Verify Email Address</a>
    </div>
    <div class="footer">
        <p>&copy; MyApp {{ date('Y') }}</p>
    </div>
</body>
</html>
