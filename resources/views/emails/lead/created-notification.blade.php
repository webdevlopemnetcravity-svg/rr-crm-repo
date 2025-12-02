<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Lead Assigned</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
        <h1 style="color: #2c3e50; margin-top: 0;">New Lead Assigned to You</h1>
    </div>
    
    <div style="background-color: #ffffff; padding: 20px; border: 1px solid #dee2e6; border-radius: 5px;">
        <p>Hello {{ $lead->leadOwner->name ?? 'Team Member' }},</p>
        
        <p>A new lead has been successfully created and assigned to you in the system.</p>
        
        <h2 style="color: #2c3e50; margin-top: 20px;">Lead Information:</h2>
        <ul style="list-style-type: none; padding-left: 0;">
            <li style="margin-bottom: 10px;"><strong>Lead ID:</strong> #{{ $lead->id }}</li>
            <li style="margin-bottom: 10px;"><strong>Client Name:</strong> {{ $lead->client_name ?? 'N/A' }}</li>
            <li style="margin-bottom: 10px;"><strong>Email:</strong> {{ $lead->client_email ?? 'N/A' }}</li>
            <li style="margin-bottom: 10px;"><strong>Mobile:</strong> {{ $lead->mobile ?? 'N/A' }}</li>
            <li style="margin-bottom: 10px;"><strong>Created By:</strong> {{ $lead->addedBy->name ?? 'System' }}</li>
            <li style="margin-bottom: 10px;"><strong>Created Date:</strong> {{ $lead->created_at->format('F d, Y h:i A') }}</li>
        </ul>
        
        <div style="background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; padding: 15px; margin: 20px 0;">
            <p style="margin: 0; color: #155724;"><strong>Status:</strong> All 9 steps have been completed.</p>
        </div>
        
        <p>This lead has been assigned to you. Please review the lead details in the system and take appropriate action.</p>
        
        @php
            $viewLeadUrl = route('lead-details.index', ['id' => $lead->id]);
            if (isset($lead->company) && $lead->company) {
                $viewLeadUrl = getDomainSpecificUrl($viewLeadUrl, $lead->company);
            }
        @endphp
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $viewLeadUrl }}" style="display: inline-block; background-color: #007bff; color: #ffffff; text-decoration: none; padding: 12px 30px; border-radius: 5px; font-weight: bold; font-size: 16px;">View Lead</a>
        </div>
        
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
