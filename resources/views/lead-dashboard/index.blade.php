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
            .dashboard-section-card {
                background: #FFFFFF;
                border-radius: 12px;
                box-shadow: 0px 6px 18px 0px rgb(0 0 0 / 3%);
                padding: 20px;
                margin-bottom: 20px;
            }
            .dashboard-section-title {
                font-family: 'Inter', sans-serif;
                font-weight: 600;
                font-size: 16px;
                line-height: 1.21em;
                color: #000000;
                margin-bottom: 20px;
            }
            .funnel-container {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }
            .funnel-stage {
                display: flex;
                align-items: center;
                gap: 15px;
                padding: 12px 15px;
                background: #F8F9FA;
                border-radius: 8px;
                border-left: 4px solid #713ED9;
            }
            .funnel-stage-label {
                font-family: 'Inter', sans-serif;
                font-weight: 500;
                font-size: 14px;
                color: #000000;
                min-width: 200px;
            }
            .funnel-stage-count {
                font-family: 'Inter', sans-serif;
                font-weight: 600;
                font-size: 16px;
                color: #713ED9;
                min-width: 60px;
            }
            .funnel-stage-percentage {
                font-family: 'Inter', sans-serif;
                font-weight: 400;
                font-size: 14px;
                color: #6C6C6C;
                min-width: 80px;
            }
            .funnel-bar {
                flex: 1;
                height: 24px;
                background: #E9ECEF;
                border-radius: 4px;
                overflow: hidden;
                position: relative;
            }
            .funnel-bar-fill {
                height: 100%;
                background: linear-gradient(90deg, #713ED9 0%, #9D6FE8 100%);
                transition: width 0.3s ease;
            }
            .task-tracker-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 15px;
                background: #FFFFFF;
                border-radius: 8px;
                border: 1px solid #E9ECEF;
                margin-bottom: 12px;
            }
            .task-tracker-item.overdue {
                background: #FFF5F5;
                border-color: #DF3046;
                border-left: 4px solid #DF3046;
            }
            .task-tracker-label {
                font-family: 'Inter', sans-serif;
                font-weight: 500;
                font-size: 14px;
                color: #000000;
            }
            .task-tracker-value {
                font-family: 'Inter', sans-serif;
                font-weight: 600;
                font-size: 16px;
                color: #DF3046;
            }
            .task-tracker-value.normal {
                color: #713ED9;
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
                .funnel-stage {
                    flex-wrap: wrap;
                }
                .funnel-stage-label {
                    min-width: 100%;
                }
            }
        </style>
        
        @php
            // Data is passed from controller
            $totalLeads = $totalLeads ?? 0;
            $newLeadsToday = $newLeadsToday ?? 0;
            $newLeadsThisWeek = $newLeadsThisWeek ?? 0;
            $closedLeads = $closedLeads ?? 0;
            $followUpsToday = $followUpsToday ?? 0;
            $revenueGenerated = $revenueGenerated ?? 0;
            
            // Lead Status Funnel Data
            $funnelStages = $funnelData ?? [];
            $openLeadsCount = $openLeadsCount ?? 0;
            
            // Country/Visa Type Data
            $visaTypes = $visaTypes ?? [
                'PR' => 0,
                'Student Visa' => 0,
                'Visit Visa' => 0,
                'Work Permit' => 0
            ];
            $countries = $countries ?? [
                'Australia' => 0,
                'New Zealand' => 0
            ];
            
            // Source-wise Leads
            $leadSources = $leadSources ?? [
                'Facebook' => 0,
                'Google Ads' => 0,
                'Walk-in' => 0,
                'WhatsApp Inquiry' => 0,
                'Reference' => 0,
                'Website' => 0,
                'Email Marketing' => 0
            ];
            
            // Follow-up & Task Tracker
            $todayFollowups = $todayFollowups ?? 0;
            $overdueFollowups = $overdueFollowups ?? 0;
            $upcomingMeetings = $upcomingMeetings ?? 0;
            $pendingCalls = $pendingCalls ?? 0;
            
            // Revenue & Payment Analytics (Admin only)
            $totalExpectedRevenue = $totalExpectedRevenue ?? 0;
            $collectedPayments = $collectedPayments ?? 0;
            $pendingPayments = $pendingPayments ?? 0;
            $monthlyRevenue = $monthlyRevenue ?? [];
        @endphp
        
        <!-- TOP SUMMARY CARDS (KPIs) -->
        <div class="row">
            <!-- Total Leads Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <x-cards.widget title="Total Leads" :value="$totalLeads" icon="users">
                </x-cards.widget>
            </div>

            <!-- New Leads Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <x-cards.widget title="New Leads this week" :value="$newLeadsThisWeek" icon="users">
                </x-cards.widget>
            </div>

            <!-- Closed Leads Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <x-cards.widget title="Closed Leads" :value="$closedLeads" icon="check-circle">
                </x-cards.widget>
            </div>

            <!-- Follow-ups Today Card (CONSULTANT only) -->
            @if($isConsultant)
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <x-cards.widget title="Follow-ups Today" :value="$followUpsToday" icon="clock">
                </x-cards.widget>
            </div>
            @endif

            <!-- Revenue Generated Card (ADMIN only) -->
            @if($isAdmin)
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <x-cards.widget title="Revenue Generated (₹)" :value="'₹' . number_format($revenueGenerated ?? 0, 0)" icon="dollar-sign">
                </x-cards.widget>
            </div>
            @endif
        </div>

        <!-- LEAD STATUS FUNNEL -->
        <div class="row mt-3">
            <div class="col-12">
                <x-cards.data title="Lead Status Funnel">
                    <div class="row">
                        <!-- Left Column - 7 Statuses -->
                        <div class="col-lg-6 col-md-12 pr-lg-3">
                            <div class="funnel-container">
                                @php
                                    $leftStages = array_slice($funnelStages, 0, 7, true);
                                @endphp
                                @foreach($leftStages as $stageName => $stageCount)
                                    @php
                                        $percentage = $openLeadsCount > 0 ? round(($stageCount / $openLeadsCount) * 100, 1) : 0;
                                        $barWidth = $openLeadsCount > 0 ? ($stageCount / $openLeadsCount) * 100 : 0;
                                    @endphp
                                    <div class="funnel-stage">
                                        <div class="funnel-stage-label">{{ $stageName }}</div>
                                        <div class="funnel-stage-count">{{ $stageCount }}</div>
                                        <div class="funnel-bar">
                                            <div class="funnel-bar-fill" style="width: {{ $barWidth }}%"></div>
                                        </div>
                                        <div class="funnel-stage-percentage">{{ $percentage }}%</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Right Column - 6 Statuses -->
                        <div class="col-lg-6 col-md-12 pl-lg-3">
                            <div class="funnel-container">
                                @php
                                    $rightStages = array_slice($funnelStages, 7, 6, true);
                                @endphp
                                @foreach($rightStages as $stageName => $stageCount)
                                    @php
                                        $percentage = $openLeadsCount > 0 ? round(($stageCount / $openLeadsCount) * 100, 1) : 0;
                                        $barWidth = $openLeadsCount > 0 ? ($stageCount / $openLeadsCount) * 100 : 0;
                                    @endphp
                                    <div class="funnel-stage">
                                        <div class="funnel-stage-label">{{ $stageName }}</div>
                                        <div class="funnel-stage-count">{{ $stageCount }}</div>
                                        <div class="funnel-bar">
                                            <div class="funnel-bar-fill" style="width: {{ $barWidth }}%"></div>
                                        </div>
                                        <div class="funnel-stage-percentage">{{ $percentage }}%</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </x-cards.data>
            </div>
        </div>

        <!-- COUNTRY / VISA TYPE ANALYTICS -->
        <div class="row mt-3">
            <div class="col-sm-12 col-lg-6">
                <x-cards.data title="Visa Types">
                    <div id="visa-types-chart" style="height: 300px;"></div>
                </x-cards.data>
            </div>
            <div class="col-sm-12 col-lg-6">
                <x-cards.data title="Top Countries">
                    <div id="countries-chart" style="height: 300px;"></div>
                </x-cards.data>
            </div>
        </div>

        <!-- SOURCE-WISE LEADS -->
        <div class="row mt-3">
            <div class="col-12">
                <x-cards.data title="Source-wise Leads">
                    <div id="source-leads-chart" style="height: 350px;"></div>
                </x-cards.data>
            </div>
        </div>

        <!-- FOLLOW-UP & TASK TRACKER AND REVENUE OVERVIEW -->
        <div class="row mt-3">
            <!-- FOLLOW-UP & TASK TRACKER -->
            <div class="col-sm-12 col-lg-6">
                <x-cards.data title="Follow-up & Task Tracker">
                    <div class="task-tracker-item {{ $overdueFollowups > 0 ? 'overdue' : '' }}">
                        <span class="task-tracker-label">Overdue Follow-ups</span>
                        <span class="task-tracker-value">{{ $overdueFollowups }}</span>
                    </div>
                    <div class="task-tracker-item">
                        <span class="task-tracker-label">Today Follow-ups</span>
                        <span class="task-tracker-value normal">{{ $todayFollowups }}</span>
                    </div>
                    <div class="task-tracker-item">
                        <span class="task-tracker-label">Upcoming Meetings</span>
                        <span class="task-tracker-value normal">{{ $upcomingMeetings }}</span>
                    </div>
                    <div class="task-tracker-item">
                        <span class="task-tracker-label">Pending Calls</span>
                        <span class="task-tracker-value normal">{{ $pendingCalls }}</span>
                    </div>
                </x-cards.data>
            </div>

            <!-- REVENUE OVERVIEW (ADMIN ONLY) -->
            @if($isAdmin)
            <div class="col-sm-12 col-lg-6">
                <x-cards.data title="Revenue Overview">
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #F8F9FA; border-radius: 8px;">
                            <span style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; color: #000000;">Total Expected Revenue</span>
                            <span style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 16px; color: #713ED9;">₹{{ number_format($totalExpectedRevenue, 0) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #F8F9FA; border-radius: 8px;">
                            <span style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; color: #000000;">Collected Payments</span>
                            <span style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 16px; color: #1A8761;">₹{{ number_format($collectedPayments, 0) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #F8F9FA; border-radius: 8px;">
                            <span style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; color: #000000;">Pending Payments</span>
                            <span style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 16px; color: #DF3046;">₹{{ number_format($pendingPayments, 0) }}</span>
                        </div>
                    </div>
                </x-cards.data>
            </div>
            @endif
        </div>
    </div>
    <!-- CONTENT WRAPPER END -->

    <script src="{{ asset('vendor/jquery/frappe-charts.min.iife.js') }}"></script>
    <script src="{{ asset('vendor/jquery/Chart.min.js') }}"></script>
    <script>
        // Visa Types Chart
        @php
            $visaTypesData = [
                'labels' => array_keys($visaTypes),
                'values' => array_values($visaTypes),
                'colors' => ['#713ED9', '#0CC8F1', '#1A8761', '#FFBF09']
            ];
        @endphp
        @if(array_sum($visaTypesData['values']) > 0)
        var visaTypesData = {
            labels: [
                @foreach($visaTypesData['labels'] as $label)
                    "{{ $label }}",
                @endforeach
            ],
            datasets: [{
                name: "Visa Types",
                values: [
                    @foreach($visaTypesData['values'] as $value)
                        {{ $value }},
                    @endforeach
                ],
                chartType: 'bar'
            }]
        };
        var visaTypesChart = new frappe.Chart("#visa-types-chart", {
            data: visaTypesData,
            type: 'bar',
            height: 300,
            barOptions: {
                stacked: false,
                spaceRatio: 0.3
            },
            valuesOverPoints: 1,
            axisOptions: {
                yAxisMode: 'tick',
                xAxisMode: 'tick',
                xIsSeries: 0
            },
            colors: [
                @foreach($visaTypesData['colors'] as $color)
                    "{{ $color }}",
                @endforeach
            ]
        });
        @else
        document.getElementById('visa-types-chart').innerHTML = '<div class="align-items-center d-flex flex-column text-lightest p-20" style="height: 300px;"><i class="side-icon bi bi-bar-chart"></i><div class="f-15 mt-4">- Not Enough Data -</div></div>';
        @endif

        // Countries Chart
        @php
            $countriesData = [
                'labels' => array_keys($countries),
                'values' => array_values($countries),
                'colors' => ['#713ED9', '#0CC8F1']
            ];
        @endphp
        @if(array_sum($countriesData['values']) > 0)
        var countriesData = {
            labels: [
                @foreach($countriesData['labels'] as $label)
                    "{{ $label }}",
                @endforeach
            ],
            datasets: [{
                name: "Countries",
                values: [
                    @foreach($countriesData['values'] as $value)
                        {{ $value }},
                    @endforeach
                ],
                chartType: 'bar'
            }]
        };
        var countriesChart = new frappe.Chart("#countries-chart", {
            data: countriesData,
            type: 'bar',
            height: 300,
            barOptions: {
                stacked: false,
                spaceRatio: 0.3
            },
            valuesOverPoints: 1,
            axisOptions: {
                yAxisMode: 'tick',
                xAxisMode: 'tick',
                xIsSeries: 0
            },
            colors: [
                @foreach($countriesData['colors'] as $color)
                    "{{ $color }}",
                @endforeach
            ]
        });
        @else
        document.getElementById('countries-chart').innerHTML = '<div class="align-items-center d-flex flex-column text-lightest p-20" style="height: 300px;"><i class="side-icon bi bi-bar-chart"></i><div class="f-15 mt-4">- Not Enough Data -</div></div>';
        @endif

        // Source-wise Leads Chart
        @php
            $sourceLeadsData = [
                'labels' => array_keys($leadSources),
                'values' => array_values($leadSources),
                'colors' => ['#713ED9', '#0CC8F1', '#1A8761', '#FFBF09', '#DF3046', '#1B855B', '#9D6FE8']
            ];
        @endphp
        @if(array_sum($sourceLeadsData['values']) > 0)
        var sourceLeadsData = {
            labels: [
                @foreach($sourceLeadsData['labels'] as $label)
                    "{{ $label }}",
                @endforeach
            ],
            datasets: [{
                name: "Source-wise Leads",
                values: [
                    @foreach($sourceLeadsData['values'] as $value)
                        {{ $value }},
                    @endforeach
                ],
                chartType: 'bar'
            }]
        };
        var sourceLeadsChart = new frappe.Chart("#source-leads-chart", {
            data: sourceLeadsData,
            type: 'bar',
            height: 350,
            barOptions: {
                stacked: false,
                spaceRatio: 0.3
            },
            valuesOverPoints: 1,
            axisOptions: {
                yAxisMode: 'tick',
                xAxisMode: 'tick',
                xIsSeries: 0
            },
            colors: [
                @foreach($sourceLeadsData['colors'] as $color)
                    "{{ $color }}",
                @endforeach
            ]
        });
        @else
        document.getElementById('source-leads-chart').innerHTML = '<div class="align-items-center d-flex flex-column text-lightest p-20" style="height: 350px;"><i class="side-icon bi bi-bar-chart"></i><div class="f-15 mt-4">- Not Enough Data -</div></div>';
        @endif

    </script>
@endsection
