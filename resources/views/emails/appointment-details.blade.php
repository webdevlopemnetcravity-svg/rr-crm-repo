<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <!-- Logo Section -->
    <div style="text-align: center; padding: 20px 0 30px 0;">
        <img src="https://lh3.googleusercontent.com/d/1o50KgJxSNFJCYEUOTEx33wBYK5LLD2Wc" alt="{{ config('app.name') }}" style="max-width: 270px; height: auto; display: block; margin: 0 auto;" />
    </div>
    
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
        <h1 style="color: #2c3e50; margin-top: 0;">Appointment Confirmed</h1>
    </div>
    
    <div style="background-color: #ffffff; padding: 20px; border: 1px solid #dee2e6; border-radius: 5px;">
        <p>Hi {{ $notifiableName }},</p>
        
        <p>We're delighted to confirm your appointment with RR Patel Overseas & Education. Your registration has been successfully completed, and we're excited to welcome you onboard! 🎉</p>
        
        <p>Please find your appointment details below:</p>
        
        <div style="margin: 20px 0; padding: 15px; background-color: #f9f9f9; border-left: 4px solid #1f75cb;">
            {!! $content !!}
        </div>
        
        @if (!empty($url))
        <div style="text-align: center; margin-top: 30px; margin-bottom: 30px;">
            <a href="{{ $url }}" target="_blank" style="background-color: #1f75cb; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">{{ $actionText }}</a>
        </div>
        @endif
        
        @if (!empty($meetingId) || !empty($meetingPassword))
        <div style="margin: 20px 0; padding: 15px; background-color: #f9f9f9; border-left: 4px solid #1f75cb;">
            <p style="margin-top: 0; font-weight: bold; color: #2c3e50;">Meeting Credentials:</p>
            @if (!empty($meetingId))
            <p style="margin: 5px 0;"><strong>Meeting ID:</strong> {{ $meetingId }}</p>
            @endif
            @if (!empty($meetingPassword))
            <p style="margin: 5px 0;"><strong>Meeting Password:</strong> {{ $meetingPassword }}</p>
            @endif
        </div>
        @endif
        
        <div style="margin: 20px 0; padding: 15px; background-color: #f9f9f9; border-left: 4px solid #1f75cb;">
            <p style="margin-top: 0; font-weight: bold; color: #2c3e50;">Important Notes:</p>
            <p style="margin: 5px 0;">• Please join the meeting a few minutes early to ensure everything is working properly</p>
            <p style="margin: 5px 0;">• Ensure you have a stable internet connection for the best experience</p>
            <p style="margin: 5px 0;">• For any questions or assistance, please contact your consultant as mentioned above</p>
        </div>
        
        <p style="margin-top: 30px;">Best regards,<br>{{ config('app.name') }}</p>
    </div>
</body>
</html>
