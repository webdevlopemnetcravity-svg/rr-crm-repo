<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #000000;
            background: #FFFFFF;
            padding: 20px;
            line-height: 1.5;
        }
        
        .invoice-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
        }
        
        .invoice-title-section {
            flex: 0 0 auto;
        }
        
        .invoice-main-title {
            color: #1E40AF;
            font-size: 32px;
            font-weight: 700;
            margin: 0 0 8px 0;
        }
        
        .invoice-lead-number {
            color: #000000;
            font-size: 14px;
            margin: 0;
        }
        
        .invoice-company-info {
            flex: 0 0 auto;
            text-align: right;
        }
        
        .company-logo-image {
            width: auto;
            height: 62px;
            object-fit: contain;
        }
        
        .invoice-divider {
            border-top: 1px solid #dcecff;
            margin: 24px 0;
            border-bottom: none;
        }
        
        .invoice-section-title {
            color: #1D82F5;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        
        .invoice-details-grid {
            width: 100%;
            margin-bottom: 24px;
        }
        
        .invoice-detail-item {
            margin-bottom: 16px;
        }
        
        .invoice-detail-label {
            color: #6C6C6C;
            font-size: 12px;
            margin-bottom: 4px;
            font-weight: 400;
        }
        
        .invoice-detail-value {
            color: #000000;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.5;
            word-wrap: break-word;
        }
        
        .invoice-service-section {
            width: 100%;
            margin-bottom: 24px;
        }
        
        .invoice-service-item {
            margin-bottom: 12px;
        }
        
        .invoice-amount-table {
            background-color: #FFFFFF;
            border: 1px solid #dcecff;
            border-radius: 4px;
            padding: 16px;
        }
        
        .invoice-amount-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            color: #000000;
            font-size: 14px;
        }
        
        .invoice-amount-row.total {
            border-top: 1px solid #dcecff;
            padding-top: 12px;
            margin-top: 8px;
            font-size: 16px;
            font-weight: 600;
        }
        
        .invoice-amount-label {
            color: #000000;
            font-size: 14px;
            text-align: left;
        }
        
        .invoice-amount-value {
            color: #000000;
            font-size: 14px;
            font-weight: 500;
            text-align: right;
        }
        
        .invoice-amount-label.total,
        .invoice-amount-value.total {
            font-size: 16px;
            font-weight: 600;
        }
        
        .invoice-installment-note {
            color: #6C6C6C;
            font-size: 12px;
            text-align: left;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #dcecff;
        }
        
        .invoice-footer-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dcecff;
        }
        
        .footer-label {
            color: #000000;
            font-size: 12px;
            margin-bottom: 4px;
        }
        
        .footer-toll-free {
            color: #1E40AF;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .footer-email {
            color: #000000;
            font-size: 12px;
        }
        
        .footer-address {
            color: #000000;
            font-size: 12px;
            line-height: 1.5;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }
        
        .col-md-3 {
            width: 25%;
            padding: 0 15px;
        }
        
        .col-md-4 {
            width: 33.333333%;
            padding: 0 15px;
        }
        
        .col-md-6 {
            width: 50%;
            padding: 0 15px;
        }
        
        .mb-3 {
            margin-bottom: 16px;
        }
        
        .mb-4 {
            margin-bottom: 24px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <!-- Invoice Title and Company Info Section -->
    <div class="invoice-header-row">
        <!-- Invoice Title Section (Left) -->
        <div class="invoice-title-section">
            <h2 class="invoice-main-title">INVOICE</h2>
            <p class="invoice-lead-number">{{ $leadId }}</p>
        </div>
        <!-- Company Logo Section (Right) -->
        <div class="invoice-company-info">
            <div>
                <img src="{{ $companyLogo }}" alt="Company Logo" class="company-logo-image">
            </div>
        </div>
    </div>
    <hr class="invoice-divider">
    
    <!-- Client and Invoice Details Section -->
    <div class="invoice-details-grid">
        <div class="row">
            <!-- Row 1: Client Name | Email | Phone | Invoice Date -->
            <div class="col-md-3 mb-3">
                <div class="invoice-detail-item">
                    <div class="invoice-detail-label">Client Name</div>
                    <div class="invoice-detail-value">{{ $account->client_name ?? '--' }}</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="invoice-detail-item">
                    <div class="invoice-detail-label">Email</div>
                    <div class="invoice-detail-value">{{ $account->email ?? '--' }}</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="invoice-detail-item">
                    <div class="invoice-detail-label">Phone</div>
                    <div class="invoice-detail-value">{{ $account->phone ?? '--' }}</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="invoice-detail-item">
                    <div class="invoice-detail-label">Invoice Date</div>
                    <div class="invoice-detail-value">{{ $account->invoice_date ? $account->invoice_date->format($dateFormat ?? 'd-m-Y') : '--' }}</div>
                </div>
            </div>
            <!-- Row 2: Bill to | Invoice Belongs To | Address -->
            <div class="col-md-3 mb-3">
                <div class="invoice-detail-item">
                    <div class="invoice-detail-label">Bill to</div>
                    <div class="invoice-detail-value">{{ $account->bill_to ?? '--' }}</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="invoice-detail-item">
                    <div class="invoice-detail-label">Invoice Belongs To</div>
                    <div class="invoice-detail-value">{{ $account->agentUser ? $account->agentUser->name : '--' }}</div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="invoice-detail-item">
                    <div class="invoice-detail-label">Address</div>
                    <div class="invoice-detail-value">{{ $account->address ?? '--' }}</div>
                </div>
            </div>
        </div>
    </div>
    <hr class="invoice-divider">
    
    <!-- Details Section -->
    <div class="invoice-service-section">
        <h4 class="invoice-section-title">DETAILS</h4>
        <div class="invoice-service-item mb-3">
            <div class="invoice-detail-label">Service</div>
            <div class="invoice-detail-value">{{ $account->service ?? '--' }}</div>
        </div>
        <div class="invoice-service-item">
            <div class="invoice-detail-label">Note</div>
            <div class="invoice-detail-value">{{ $account->service_description ?? $account->invoice_notes ?? '--' }}</div>
        </div>
    </div>
    <hr class="invoice-divider">
    
    <!-- Payable Amount Section -->
    <div class="invoice-payable-section">
        <h4 class="invoice-section-title">PAYABLE AMOUNT</h4>
        <div class="invoice-amount-table">
            <div class="invoice-amount-row">
                <div class="invoice-amount-label">Sub Total :</div>
                <div class="invoice-amount-value">{{ $currencySymbol }} {{ number_format($account->sub_total ?? $account->price ?? 0, 2) }}</div>
            </div>
            <div class="invoice-amount-row">
                <div class="invoice-amount-label">Discount :</div>
                <div class="invoice-amount-value">{{ $currencySymbol }} {{ number_format($account->discount_amount ?? $account->discount ?? 0, 2) }}</div>
            </div>
            <div class="invoice-amount-row">
                <div class="invoice-amount-label">Tax Amount :</div>
                <div class="invoice-amount-value">{{ $currencySymbol }} {{ number_format($account->tax_amount ?? 0, 2) }}</div>
            </div>
            <div class="invoice-amount-row total">
                <div class="invoice-amount-label total">Total Amount :</div>
                <div class="invoice-amount-value total">{{ $currencySymbol }} {{ number_format($account->total_amount ?? $account->net_amount ?? 0, 2) }}</div>
            </div>
            @if($account->installment_payment && $account->installment_months)
                <div class="invoice-installment-note">
                    Installment {{ $currencySymbol }} {{ number_format(($account->total_amount ?? $account->net_amount ?? 0) / $account->installment_months, 2) }} for {{ $account->installment_months }} months
                </div>
            @endif
        </div>
    </div>
    <hr class="invoice-divider">
    
    <!-- Footer Section -->
    <div class="invoice-footer-section">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div>
                    <img src="{{ $companyLogo }}" alt="Company Logo" class="company-logo-image">
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="footer-label">Toll-Free Number</div>
                <div class="footer-toll-free">{{ $companyPhone ?? '1800 571 2844' }}</div>
                <div class="footer-email">{{ $companyEmail ?? 'info.rrpei@gmail.com' }}</div>
            </div>
            <div class="col-md-4 mb-3 text-right">
                <div class="footer-address">{{ $companyAddress ?? '3rd Floor, Aaron Spectra, 302, Rajpath Rangoli Rd, behind Rajpath Club, Bodakdev, Ahmedabad, Gujarat 380059' }}</div>
            </div>
        </div>
    </div>
</body>
</html>

