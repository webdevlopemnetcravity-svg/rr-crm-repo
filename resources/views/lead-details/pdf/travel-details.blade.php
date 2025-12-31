<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Travel Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 270px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 5px;
        }
        .info-row {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 200px;
        }
        .info-value {
            display: inline-block;
        }
        .two-column {
            display: table;
            width: 100%;
        }
        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ $logoUrl }}" alt="Logo" class="logo">
        <div class="title">Travel Details</div>
    </div>

    <div class="section">
        <div class="section-title">Travel Information</div>
        <div class="two-column">
            <div class="column">
                <div class="info-row">
                    <span class="info-label">Purpose of Trip:</span>
                    <span class="info-value">{{ $getValue($travelDetails->purpose_of_trip) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Place To Visit:</span>
                    <span class="info-value">{{ $getValue($travelDetails->place_to_visit) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date of Arrival:</span>
                    <span class="info-value">{{ $formatDate($travelDetails->date_of_arrival) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Arrival Flight:</span>
                    <span class="info-value">{{ $getValue($travelDetails->arrival_flight) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Arrival City:</span>
                    <span class="info-value">{{ $getValue($travelDetails->arrival_city) }}</span>
                </div>
            </div>
            <div class="column">
                <div class="info-row">
                    <span class="info-label">Date of Departure:</span>
                    <span class="info-value">{{ $formatDate($travelDetails->date_of_departure) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Departure Flight:</span>
                    <span class="info-value">{{ $getValue($travelDetails->departure_flight) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Departure City:</span>
                    <span class="info-value">{{ $getValue($travelDetails->departure_city) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone Number (other country):</span>
                    <span class="info-value">{{ $getValue($travelDetails->phone_number_other_country) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Address Where You Will Stay</div>
        <div class="info-row">
            <span class="info-label">Address:</span>
            <span class="info-value">{{ $getValue($travelDetails->address_stay) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">City:</span>
            <span class="info-value">{{ $getValue($travelDetails->city) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">State:</span>
            <span class="info-value">{{ $getValue($travelDetails->state) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Postal/Zip Code:</span>
            <span class="info-value">{{ $getValue($travelDetails->postal_code) }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Personal Information</div>
        <div class="info-row">
            <span class="info-label">Person Paying For Trip:</span>
            <span class="info-value">{{ $getValue($travelDetails->person_paying) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Mother in Country:</span>
            <span class="info-value">{{ $getValue($travelDetails->mother_in_country ? ucfirst($travelDetails->mother_in_country) : null) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Immediate Relatives:</span>
            <span class="info-value">{{ $getValue($travelDetails->immediate_relatives ? ucfirst($travelDetails->immediate_relatives) : null) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Other Relatives:</span>
            <span class="info-value">{{ $getValue($travelDetails->other_relatives ? ucfirst($travelDetails->other_relatives) : null) }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Generated on {{ date('d-M-Y h:i A') }}</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>

