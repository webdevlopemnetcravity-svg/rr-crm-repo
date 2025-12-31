<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Details Notification</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f5f5f5;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <!-- Main Container -->
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="background-color: #ffffff; max-width: 600px; width: 100%;">
                    
                    <!-- Logo Section -->
                    <tr>
                        <td align="center" style="padding: 40px 20px 20px 20px;">
                            <img src="https://lh3.googleusercontent.com/d/1o50KgJxSNFJCYEUOTEx33wBYK5LLD2Wc" alt="{{ config('app.name') }}" style="max-width: 270px; height: auto; display: block;" />
                        </td>
                    </tr>

                    <!-- Content Section -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f0f0f0; border-radius: 8px;">
                                <tr>
                                    <td style="padding: 30px;">
                                        <h1 style="color: #2c3e50; margin-top: 0; font-size: 24px;">Congratulations! Find your travel details</h1>
                                        
                                        <p style="color: #333; font-size: 16px; line-height: 1.6;">
                                            Hi {{ $leadName ?? 'Client' }},
                                        </p>
                                        
                                        <p style="color: #333; font-size: 16px; line-height: 1.6;">
                                            As per your request, here are the additional details you asked for.
                                        </p>
                                        
                                        <p style="color: #333; font-size: 16px; line-height: 1.6;">
                                            For more details, please connect with {{ $consultantName ?? 'Our Team' }} at {{ $consultantNumber ?? 'N/A' }}.
                                        </p>
                                        
                                        <p style="color: #333; font-size: 16px; line-height: 1.6; margin-top: 20px;">
                                            Please find your travel details attached in the PDF document.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px 40px; text-align: center; color: #6c757d; font-size: 14px;">
                            <p style="margin: 0;">
                                @lang('email.regards'),<br>
                                <strong>{{ config('app.name') }}</strong>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

