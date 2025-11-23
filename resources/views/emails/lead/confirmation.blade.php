<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
        <h1 style="color: #2c3e50; margin-top: 0;">Lead Confirmation</h1>
    </div>
    
    <div style="background-color: #ffffff; padding: 20px; border: 1px solid #dee2e6; border-radius: 5px;">
        <p>Hello {{ $lead->client_name ?? 'Valued Customer' }},</p>
        
        <p>Thank you for your interest! Your lead has been successfully created in our system.</p>
        
        <h2 style="color: #2c3e50; margin-top: 20px;">Lead Details:</h2>
        <ul style="list-style-type: none; padding-left: 0;">
            <li style="margin-bottom: 10px;"><strong>Lead ID:</strong> #{{ $lead->id }}</li>
            <li style="margin-bottom: 10px;"><strong>Name:</strong> {{ $lead->client_name ?? 'N/A' }}</li>
            <li style="margin-bottom: 10px;"><strong>Email:</strong> 
                @php
                    $email = $lead->client_email ?? 'N/A';
                    if ($lead->step_1_data) {
                        $step1Data = is_string($lead->step_1_data) ? json_decode($lead->step_1_data, true) : $lead->step_1_data;
                        if (is_array($step1Data) && isset($step1Data['email_address'])) {
                            $email = $step1Data['email_address'];
                        }
                    }
                @endphp
                {{ $email }}
            </li>
            <li style="margin-bottom: 10px;"><strong>Mobile:</strong> {{ $lead->mobile ?? 'N/A' }}</li>
            <li style="margin-bottom: 10px;"><strong>Created Date:</strong> {{ $lead->created_at->format('F d, Y') }}</li>
        </ul>
        
        <p>Our team will review your information and get back to you shortly. If you have any questions or need to update your information, please don't hesitate to contact us.</p>
        
        <p>Thank you for choosing us!</p>
        
        <p style="margin-top: 30px;">
            @lang('email.regards'),<br>
            <strong>{{ config('app.name') }}</strong>
        </p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #6c757d; font-size: 12px;">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
