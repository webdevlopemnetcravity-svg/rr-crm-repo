<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Document</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
        <h1 style="color: #2c3e50; margin-top: 0;">Template Document</h1>
    </div>
    
    <div style="background-color: #ffffff; padding: 20px; border: 1px solid #dee2e6; border-radius: 5px;">
        <p>Hello,</p>
        
        <p>Please find the attached template document: <strong>{{ $document->name }}</strong></p>
        
        <p>If you have any questions, please feel free to contact us.</p>
        
        <p style="margin-top: 30px;">Best regards,<br>{{ config('app.name') }}</p>
    </div>
</body>
</html>

