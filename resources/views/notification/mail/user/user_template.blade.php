<!DOCTYPE html>
<html>

<head>
    <title>Email Verification</title>
</head>

<body>
    <h1>Email Verification</h1>
    <p>Hello {{ $model->first_name }},</p>
    <p>Your verification code is: <strong>{{ $template }}</strong></p>
    <p>Please use this code to verify your email address.</p>
    <p>Thank you,<br>Your App Team</p>
</body>

</html>
