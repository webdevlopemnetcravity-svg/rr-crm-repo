<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Cancelled</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <!-- Logo Section -->
    <div style="text-align: center; padding: 20px 0 30px 0;">
        <img src="https://lh3.googleusercontent.com/d/1o50KgJxSNFJCYEUOTEx33wBYK5LLD2Wc" alt="{{ config('app.name') }}" style="max-width: 270px; height: auto; display: block; margin: 0 auto;" />
    </div>
    
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
        <h1 style="color: #2c3e50; margin-top: 0;">Appointment Cancelled</h1>
    </div>
    
    <div style="background-color: #ffffff; padding: 20px; border: 1px solid #dee2e6; border-radius: 5px;">
        <p>Hi {{ $notifiableName }},</p>
        
        <p>We regret to inform you that your appointment with RR Patel Overseas & Education has been cancelled.</p>
        
        <p>Please find the cancelled appointment details below:</p>
        
        <div style="margin: 20px 0; padding: 15px; background-color: #f9f9f9; border-left: 4px solid #dc3545;">
            {!! $content !!}
        </div>
        
        <p style="margin-top: 20px;">If you have any questions or would like to reschedule, please contact your consultant as mentioned above.</p>
        
        <p style="margin-top: 30px;">Best regards,<br>{{ config('app.name') }}</p>
    </div>
</body>
</html>
