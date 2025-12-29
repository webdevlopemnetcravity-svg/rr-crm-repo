<div class="tab-content px-4 pb-4" id="statusActivityTab">
    <!-- Tab Header -->
    <div class="tab-section-header">
        <div class="tab-section-header-content">
            <div>
                <h3 class="tab-section-title">Lead Status Activity</h3>
                <div class="tab-section-subtitle-text">
                    View all status and quality changes for this lead
                </div>
            </div>
        </div>
    </div>
    
    <div class="tab-section-content">
        <!-- Sub-tabs for Status and Quality -->
        <div class="sub-tabs-container mb-4">
            <ul class="nav nav-tabs" id="statusActivitySubTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="lead-status-activity-tab" href="javascript:;" data-tab-content="statusActivityContent">
                        Lead Status
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="lead-quality-activity-tab" href="javascript:;" data-tab-content="qualityActivityContent">
                        Lead Quality
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Tab Content Area -->
        <div class="tab-content-area mt-3" id="statusActivityTabContent">
            <!-- Status Activity Content -->
            <div class="tab-pane-content active" id="statusActivityContent">
                <div id="statusActivityTimeline">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quality Activity Content -->
            <div class="tab-pane-content" id="qualityActivityContent">
                <div id="qualityActivityTimeline">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .status-activity-timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .status-activity-timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 5px;
        bottom: 0;
        width: 2px;
        background: #e0e0e0;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 30px;
        padding-left: 30px;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -20px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #42A5F5;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #42A5F5;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-item-value {
        font-size: 14px;
    }
    
    .timeline-item-remark {
        background: #f9f9f9;
        padding: 10px;
        border-radius: 4px;
        border-left: 3px solid #42A5F5;
        margin-top: 8px;
        font-size: 13px;
        color: #666;
    }
    
    .timeline-item-user {
        font-size: 12px;
        color: #999;
        margin-top: 5px;
    }
    
    .timeline-empty {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }
    
    .sub-tabs-container {
        border-bottom: 1px solid #e0e0e0;
    }
    
    .sub-tabs-container .nav-tabs {
        border-bottom: none;
    }
    
    .sub-tabs-container .nav-link {
        color: #666;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 10px 20px;
        cursor: pointer;
    }
    
    .sub-tabs-container .nav-link.active {
        color: #1976d2;
        border-bottom-color: #1976d2;
        font-weight: 600;
    }
    
    /* Simple tab content visibility */
    #statusActivityTabContent .tab-pane-content {
        display: none;
    }
    
    #statusActivityTabContent .tab-pane-content.active {
        display: block !important;
    }
</style>

<script>
    $(document).ready(function() {
        var leadId = @json(isset($lead) && $lead ? $lead->id : null);
        
        if (!leadId) {
            $('#statusActivityTimeline, #qualityActivityTimeline').html('<div class="timeline-empty">Lead ID not found.</div>');
            return;
        }
        
        var statusLoaded = false;
        var qualityLoaded = false;
        
        // Simple tab switching function
        function switchTab(tabId) {
            // Remove active from all tabs and content
            $('#statusActivitySubTabs .nav-link').removeClass('active');
            $('#statusActivityTabContent .tab-pane-content').removeClass('active');
            
            // Add active to clicked tab
            $('#' + tabId).addClass('active');
            
            // Show corresponding content
            var contentId = $('#' + tabId).data('tab-content');
            $('#' + contentId).addClass('active');
            
            // Load data if needed
            if (contentId === 'statusActivityContent' && !statusLoaded) {
                loadStatusActivity();
            } else if (contentId === 'qualityActivityContent' && !qualityLoaded) {
                loadQualityActivity();
            }
        }
        
        // Handle tab clicks
        $(document).on('click', '#lead-status-activity-tab, #lead-quality-activity-tab', function(e) {
            e.preventDefault();
            var tabId = $(this).attr('id');
            switchTab(tabId);
        });
        
        // Function to check if status activity tab is active and load data
        function checkAndLoadStatusActivity() {
            if ($('#statusActivityTab').hasClass('active') && $('#statusActivityContent').hasClass('active') && !statusLoaded) {
                loadStatusActivity();
            }
        }
        
        // Function to check if quality activity tab is active and load data
        function checkAndLoadQualityActivity() {
            if ($('#statusActivityTab').hasClass('active') && $('#qualityActivityContent').hasClass('active') && !qualityLoaded) {
                loadQualityActivity();
            }
        }
        
        // Load status activity when main tab is clicked
        $(document).on('click', '.nav-item-lead[data-tab="statusActivityTab"]', function() {
            setTimeout(function() {
                if ($('#statusActivityTab').hasClass('active')) {
                    checkAndLoadStatusActivity();
                }
            }, 200);
        });
        
        function loadStatusActivity() {
            if (statusLoaded) {
                return;
            }
            
            statusLoaded = true;
            console.log('Loading status activity for lead:', leadId);
            
            $.easyAjax({
                url: "{{ route('lead-details.status-activity', ':id') }}".replace(':id', leadId),
                type: "GET",
                blockUI: false,
                success: function(response) {
                    if (response.status == 'success') {
                        renderTimeline('#statusActivityTimeline', response.data, 'status');
                    } else {
                        $('#statusActivityTimeline').html('<div class="timeline-empty">Failed to load status activity.</div>');
                        statusLoaded = false;
                    }
                },
                error: function() {
                    $('#statusActivityTimeline').html('<div class="timeline-empty">Failed to load status activity.</div>');
                    statusLoaded = false;
                }
            });
        }
        
        function loadQualityActivity() {
            if (qualityLoaded) {
                return;
            }
            
            qualityLoaded = true;
            console.log('Loading quality activity for lead:', leadId);
            
            $.easyAjax({
                url: "{{ route('lead-details.quality-activity', ':id') }}".replace(':id', leadId),
                type: "GET",
                blockUI: false,
                success: function(response) {
                    if (response.status == 'success') {
                        renderTimeline('#qualityActivityTimeline', response.data, 'quality');
                    } else {
                        $('#qualityActivityTimeline').html('<div class="timeline-empty">Failed to load quality activity.</div>');
                        qualityLoaded = false;
                    }
                },
                error: function() {
                    $('#qualityActivityTimeline').html('<div class="timeline-empty">Failed to load quality activity.</div>');
                    qualityLoaded = false;
                }
            });
        }
        
        // Status colors from list page
        var statusColors = {
            "Open Lead": "#9E9E9E",
            "Consultation in Progress": "#42A5F5",
            "Meeting in Progress": "#5C6BC0",
            "Documentation": "#81C784",
            "Final Discussion": "#4CAF50",
            "Estimation": "#C0CA33",
            "Payment": "#FFC107",
            "MOU": "#FB8C00",
            "File in Process": "#64B5F6",
            "File Submission": "#00BCD4",
            "Visa Process": "#8BC34A",
            "Flying Date Received": "#4DD0E1",
            "Join/Move/Admissions": "#43A047",
            "Follow Up": "#F06292",
            "Lead Close": "#E53935"
        };
        
        // Quality colors from list page
        var qualityColors = {
            "Open": "#42A5F5",
            "In-Process": "#26C6DA",
            "On Hold": "#FFC107",
            "Plan Dropped": "#FF7043",
            "Negotiation": "#8E24AA",
            "Future Prospect": "#7CB342",
            "Ringing": "#5C6BC0",
            "Dead/Junk Lead": "#E53935",
            "Not Interested": "#F06292",
            "Rejected": "#9E9E9E"
        };
        
        function formatDateTime(dateString) {
            // Parse the ISO date string and format it as DD-MM-YYYY HH:MM AM/PM
            if (!dateString) return '';
            
            var date = new Date(dateString);
            if (isNaN(date.getTime())) {
                // If ISO parsing fails, try to parse the formatted date string
                return dateString;
            }
            
            var day = String(date.getDate()).padStart(2, '0');
            var month = String(date.getMonth() + 1).padStart(2, '0');
            var year = date.getFullYear();
            var hours24 = date.getHours();
            var minutes = String(date.getMinutes()).padStart(2, '0');
            var ampm = hours24 >= 12 ? 'PM' : 'AM';
            var hours12 = hours24 % 12;
            hours12 = hours12 ? hours12 : 12; // the hour '0' should be '12'
            
            return day + '-' + month + '-' + year + '  ' + hours12 + ':' + minutes + ' ' + ampm;
        }
        
        function renderTimeline(container, data, type) {
            var $container = $(container);
            
            if (!data || data.length === 0) {
                $container.html('<div class="timeline-empty">No ' + type + ' changes recorded yet.</div>');
                return;
            }
            
            var colorMap = type === 'status' ? statusColors : qualityColors;
            
            var html = '<div class="status-activity-timeline">';
            
            data.forEach(function(item) {
                var newValue = item.new_value || '';
                var color = colorMap[newValue] || '#9E9E9E';
                var userName = item.changed_by_user ? item.changed_by_user.name : 'Unknown';
                // Use ISO date if available, otherwise use formatted date
                var dateToFormat = item.created_at_iso || item.created_at;
                var formattedDate = formatDateTime(dateToFormat);
                
                html += '<div class="timeline-item">';
                
                // Show only new value with color
                html += '<div class="timeline-item-value" style="background-color: ' + color + '; color: white; padding: 8px 16px; border-radius: 4px; display: inline-block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">';
                html += escapeHtml(newValue);
                html += '</div>';
                
                // Updated by and date in one line
                html += '<div class="timeline-item-user">Updated by: ' + escapeHtml(userName) + ' - ' + formattedDate + '</div>';
                
                // Remark if exists
                if (item.remark) {
                    html += '<div class="timeline-item-remark">' + escapeHtml(item.remark) + '</div>';
                }
                
                html += '</div>';
            });
            
            html += '</div>';
            $container.html(html);
        }
        
        function escapeHtml(text) {
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text ? text.replace(/[&<>"']/g, function(m) { return map[m]; }) : '';
        }
        
        // Load initial data when main tab becomes active
        setTimeout(function() {
            if ($('#statusActivityTab').hasClass('active')) {
                checkAndLoadStatusActivity();
            }
        }, 500);
    });
</script>
