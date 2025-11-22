@extends('layouts.app')

@section('content')
    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        <style>
            .dashboard-card {
                background: #FFFFFF;
                border-radius: 12px;
                box-shadow: 0px 6px 18px 0px rgb(0 0 0 / 3%);
                padding: 0;
                height: 142px;
                position: relative;
                width: 100%;
            }
            .dashboard-card-title {
                font-family: 'Inter', sans-serif;
                font-weight: 600;
                font-size: 14px;
                line-height: 1.21em;
                color: #000000;
                margin: 0;
                display: flex;
                align-items: center;
                gap: 8px;
                position: absolute;
                left: 20px;
                top: 20px;
                height: 32px;
                right: 20px;
            }
            .dashboard-card-title-alt-1 {
                top: 24px;
                height: 24px;
            }
            .dashboard-card-title-alt-2 {
                top: 32px;
                height: 24px;
            }
            .dashboard-card-icon {
                width: 31px;
                height: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            .dashboard-card-icon svg {
                width: 24px;
                height: 24px;
            }
            .dashboard-card-separator {
                height: 2px;
                background: #DEDEDE;
                position: absolute;
                left: 20px;
                right: 20px;
                top: 72px;
            }
            .dashboard-card-separator-alt {
                top: 80px;
            }
            .dashboard-card-number {
                font-family: 'Inter', sans-serif;
                font-weight: 600;
                font-size: 14px;
                line-height: 1.21em;
                color: #713ED9;
                position: absolute;
                top: 31px;
                right: 20px;
                width: auto;
                height: 10px;
            }
            .dashboard-card-number-alt-1 {
                top: 39px;
            }
            .dashboard-card-number-alt-2 {
                top: 7px;
            }
            .dashboard-time-badges {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                position: absolute;
                left: 20px;
                right: 20px;
                top: 92px;
                gap: 31px;
            }
            .dashboard-time-badges-alt {
                top: 100px;
            }
            .dashboard-time-badge-item {
                display: flex;
                flex-direction: row;
                align-items: center;
                gap: 8px;
                flex-shrink: 0;
            }
            .dashboard-time-label {
                font-family: 'Inter', sans-serif;
                font-weight: 400;
                font-size: 14px;
                line-height: 1.21em;
                color: #000000;
                width: 13.41px;
                flex-shrink: 0;
            }
            .dashboard-badge {
                height: 30px;
                border-radius: 6px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Inter', sans-serif;
                font-weight: 500;
                font-size: 14px;
                line-height: 1.21em;
                color: #FFFFFF;
                padding: 10px 6px;
                min-width: auto;
                box-sizing: border-box;
                flex-shrink: 0;
            }
            .dashboard-badge-yellow {
                background: #FFBF09;
            }
            .dashboard-badge-red {
                background: #DF3046;
            }
            .dashboard-badge-green {
                background: #1A8761;
            }
            .dashboard-badge-cyan {
                background: #0CC8F1;
            }
            .dashboard-badge-dark-green {
                background: #1B855B;
            }
            .dashboard-current-month {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                position: absolute;
                left: 20px;
                right: 20px;
                top: 92px;
                gap: 31px;
            }
            .dashboard-current-month-label {
                font-family: 'Inter', sans-serif;
                font-weight: 400;
                font-size: 14px;
                line-height: 1.21em;
                color: #000000;
            }
            .dashboard-total-group {
                display: flex;
                align-items: center;
                gap: 4.16px;
                flex-shrink: 0;
            }
            .dashboard-total-label {
                font-family: 'Inter', sans-serif;
                font-weight: 400;
                font-size: 14px;
                line-height: 1.21em;
                color: #6C6C6C;
            }
            .dashboard-total-value {
                font-family: 'Inter', sans-serif;
                font-weight: 600;
                font-size: 14px;
                line-height: 1.21em;
                color: #4D4D4D;
            }
            .dashboard-row {
                margin-bottom: 15px;
            }
            .dashboard-row:last-child {
                margin-bottom: 0;
            }
            @media (max-width: 992px) {
                .dashboard-time-badges {
                    gap: 15px;
                }
                .dashboard-current-month {
                    gap: 15px;
                }
            }
            @media (max-width: 768px) {
                .dashboard-time-badges {
                    gap: 10px;
                    flex-wrap: wrap;
                }
                .dashboard-current-month {
                    gap: 10px;
                    flex-wrap: wrap;
                }
                .dashboard-time-badge-item {
                    flex: 0 0 auto;
                }
            }
        </style>
        
        <div class="row dashboard-row">
            <!-- Total Leads Card -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="dashboard-card">
                    <div class="dashboard-card-title">
                        <div class="dashboard-card-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 17L12 22L22 17" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 12L12 17L22 12" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span>Total Leads</span>
                    </div>
                    <div class="dashboard-card-number">12</div>
                    <div class="dashboard-card-separator"></div>
                    <div class="dashboard-current-month">
                        <div class="dashboard-time-badge-item">
                            <div class="dashboard-badge dashboard-badge-cyan">0</div>
                            <span class="dashboard-current-month-label">Current Month</span>
                        </div>
                        <div class="dashboard-total-group">
                            <span class="dashboard-total-label">Total</span>
                            <span class="dashboard-total-value">12</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reg. Leads Card -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="dashboard-card">
                    <div class="dashboard-card-title">
                        <span>Reg. Leads</span>
                    </div>
                    <div class="dashboard-card-separator"></div>
                    <div class="dashboard-current-month">
                        <div class="dashboard-time-badge-item">
                            <div class="dashboard-badge dashboard-badge-dark-green">0</div>
                            <span class="dashboard-current-month-label">Current Month</span>
                        </div>
                        <div class="dashboard-total-group">
                            <span class="dashboard-total-label">Total</span>
                            <span class="dashboard-total-value">5</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Open Leads Card -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="dashboard-card">
                    <div class="dashboard-card-title">
                        <div class="dashboard-card-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 17L12 22L22 17" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 12L12 17L22 12" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span>Open Leads</span>
                    </div>
                    <div class="dashboard-card-separator"></div>
                    <div class="dashboard-current-month">
                        <div class="dashboard-time-badge-item">
                            <div class="dashboard-badge dashboard-badge-yellow">0</div>
                            <span class="dashboard-current-month-label">Current Month</span>
                        </div>
                        <div class="dashboard-total-group">
                            <span class="dashboard-total-label">Total</span>
                            <span class="dashboard-total-value">7</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Open Invoices Card -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="dashboard-card">
                    <div class="dashboard-card-title">
                        <div class="dashboard-card-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 2V8H20" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span>Open Invoices</span>
                    </div>
                    <div class="dashboard-card-separator"></div>
                    <div class="dashboard-current-month">
                        <div class="dashboard-time-badge-item">
                            <div class="dashboard-badge dashboard-badge-yellow">1</div>
                            <span class="dashboard-current-month-label">Open Invoices</span>
                        </div>
                        <div class="dashboard-total-group">
                            <span class="dashboard-total-label">Total</span>
                            <span class="dashboard-total-value">12</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row dashboard-row">
            <!-- Total Actioned Lead Card -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="dashboard-card">
                    <div class="dashboard-card-title">
                        <div class="dashboard-card-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 17L12 22L22 17" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 12L12 17L22 12" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span>Total Actioned Lead</span>
                    </div>
                    <div class="dashboard-card-number">1</div>
                    <div class="dashboard-card-separator"></div>
                    <div class="dashboard-time-badges">
                        <div class="dashboard-time-badge-item">
                            <div class="dashboard-badge dashboard-badge-yellow">0</div>
                            <span class="dashboard-time-label">W</span>
                        </div>
                        <div class="dashboard-time-badge-item">
                            <div class="dashboard-badge dashboard-badge-red">0</div>
                            <span class="dashboard-time-label">M</span>
                        </div>
                        <div class="dashboard-time-badge-item">
                            <div class="dashboard-badge dashboard-badge-green">1</div>
                            <span class="dashboard-time-label">Y</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Actioned Awaited Leads Card -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="dashboard-card">
                    <div class="dashboard-card-title">
                        <div class="dashboard-card-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 17L12 22L22 17" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 12L12 17L22 12" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span>Total Actioned Awaited Leads</span>
                    </div>
                    <div class="dashboard-card-number">6</div>
                    <div class="dashboard-card-separator"></div>
                    <div class="dashboard-time-badges">
                        <div class="dashboard-time-badge-item">
                            <div class="dashboard-badge dashboard-badge-yellow">0</div>
                            <span class="dashboard-time-label">W</span>
                        </div>
                        <div class="dashboard-time-badge-item">
                            <div class="dashboard-badge dashboard-badge-red">0</div>
                            <span class="dashboard-time-label">M</span>
                        </div>
                        <div class="dashboard-time-badge-item">
                            <div class="dashboard-badge dashboard-badge-green">6</div>
                            <span class="dashboard-time-label">Y</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Followup Overdue Card -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="dashboard-card">
                    <div class="dashboard-card-title dashboard-card-title-alt-1">
                        <div class="dashboard-card-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 6V12L16 14" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span>Total Followup Overdue</span>
                    </div>
                    <div class="dashboard-card-number dashboard-card-number-alt-1">1</div>
                    <div class="dashboard-card-separator dashboard-card-separator-alt"></div>
                    <div class="dashboard-time-badges dashboard-time-badges-alt">
                        <div class="dashboard-time-badge-item">
                            <div class="dashboard-badge dashboard-badge-yellow">0</div>
                            <span class="dashboard-time-label">W</span>
                        </div>
                        <div class="dashboard-time-badge-item">
                            <div class="dashboard-badge dashboard-badge-red">0</div>
                            <span class="dashboard-time-label">M</span>
                        </div>
                        <div class="dashboard-time-badge-item">
                            <div class="dashboard-badge dashboard-badge-green">1</div>
                            <span class="dashboard-time-label">Y</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Application Done Card -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="dashboard-card">
                    <div class="dashboard-card-title dashboard-card-title-alt-2">
                        <div class="dashboard-card-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 6L9 17L4 12" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span>Total Application Done</span>
                    </div>
                    <div class="dashboard-card-number dashboard-card-number-alt-1">0</div>
                    <div class="dashboard-card-separator dashboard-card-separator-alt"></div>
                    <div class="dashboard-time-badges dashboard-time-badges-alt">
                        <div class="dashboard-time-badge-item">
                            <div class="dashboard-badge dashboard-badge-yellow">0</div>
                            <span class="dashboard-time-label">W</span>
                        </div>
                        <div class="dashboard-time-badge-item">
                            <div class="dashboard-badge dashboard-badge-red">0</div>
                            <span class="dashboard-time-label">M</span>
                        </div>
                        <div class="dashboard-time-badge-item">
                            <div class="dashboard-badge dashboard-badge-green">0</div>
                            <span class="dashboard-time-label">Y</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT WRAPPER END -->
@endsection


