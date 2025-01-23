<!DOCTYPE html>
<html>
<head>
    <title>Account Activation Denied</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 0;">
<div style="max-width: 600px; margin: 20px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; color: #F44336;">Dear {{ $username }},</h2>
    <p style="font-size: 16px; color: #333;">We regret to inform you that your account activation request has been denied.</p>

    <p style="font-size: 16px; color: #333;">**Reason for denial:**</p>
    <blockquote style="margin: 10px 0; padding: 10px 15px; background-color: #f9f9f9; border-left: 5px solid #F44336; color: #555;">
        {{ $reason }}
    </blockquote>

    <p style="font-size: 16px; color: #333;">If you believe this was a mistake or have any further questions, please don't hesitate to contact us at <a href="mailto:support@example.com">support@example.com</a>.</p>

    <p style="font-size: 16px; color: #333;">We appreciate your understanding and cooperation.</p>

    <p style="font-size: 16px; color: #333;">Best Regards,<br>The Team</p>

    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ url('/') }}" style="display: inline-block; padding: 10px 20px; background-color: #F44336; color: #fff; text-decoration: none; border-radius: 5px;">Contact Support</a>
    </div>
</div>
</body>
</html>
