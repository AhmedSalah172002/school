<!DOCTYPE html>
<html>
<head>
    <title>Lesson Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 0;">
<div style="max-width: 600px; margin: 20px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; color: #4CAF50;">Upcoming Lesson Notification</h2>
    <p style="font-size: 16px; color: #333;">Dear {{ $teacherName }},</p>

    <p style="font-size: 16px; color: #333;">
        This is a friendly reminder that your next lesson for the course <strong>{{ $courseName }}</strong> is scheduled to start in <strong>5 minutes</strong>.
    </p>

    <p style="font-size: 16px; color: #333;">
        Please ensure you are prepared and ready to join the session promptly. If you have any technical issues, reach out to support immediately.
    </p>

    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ $lessonLink ?? '/' }}" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: #fff; text-decoration: none; border-radius: 5px;">Join Lesson</a>
    </div>

    <p style="font-size: 16px; color: #333; margin-top: 20px;">
        Best Regards,<br>The Team
    </p>
</div>
</body>
</html>
