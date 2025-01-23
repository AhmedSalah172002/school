<!DOCTYPE html>
<html>
<head>
    <title>Account Activation</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 0;">
<div style="max-width: 600px; margin: 20px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; color: #4CAF50;">Congratulations, {{ $username }}!</h2>
    <p style="font-size: 16px; color: #333;">We are thrilled to inform you that your account has been successfully activated. You can now access your dashboard and start engaging with your students.</p>

    <p style="font-size: 16px; color: #333;">If you have any questions or need assistance, feel free to reach out to us.</p>

    <p style="font-size: 16px; color: #333;">Best Regards,<br>The Team</p>

    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ url('/') }}" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: #fff; text-decoration: none; border-radius: 5px;">Go to Dashboard</a>
    </div>
</div>
</body>
</html>
