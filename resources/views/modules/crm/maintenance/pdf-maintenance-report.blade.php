<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Maintenance Report #{{ $report->id }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #000000;
            font-size: 11px;
            line-height: 1.6;
            margin: 0;
            padding: 40px 45px;
            background-color: #ffffff;
        }
        

        /* Watermark */
        .watermark {
            position: fixed;
            top: 45%;
            left: 5%;
            width: 90%;
            text-align: center;
            opacity: 0.03;
            z-index: -1000;
            transform: rotate(-40deg);
            font-size: 76px;
            font-weight: 900;
            color: #135266;
            letter-spacing: 5px;
        }
        /* Top Brand Accent Stripe */
        .brand-accent-bar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: #135266;
        }
        
        /* Header section */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 25px;
        }
        .header-table td {
            padding: 0;
            vertical-align: middle;
        }
        .brand-title {
            font-size: 22px;
            font-weight: 800;
            color: #135266;
            letter-spacing: -0.5px;
            margin: 0;
            text-transform: uppercase;
        }
        .brand-subtitle {
            font-size: 10px;
            color: #222222;
            margin-top: 4px;
            font-weight: 500;
            letter-spacing: 0.2px;
        }
        .report-tag {
            font-size: 9.5px;
            font-weight: 800;
            color: #135266;
            background-color: #e6f0f2;
            padding: 5px 12px;
            border-radius: 4px;
            letter-spacing: 0.5px;
        }
        
        /* Meta Information Grid */
        .meta-strip {
            width: 100%;
            border-collapse: collapse;
            background-color: #f7fafc;
            border: 1px solid #edf2f7;
            border-radius: 6px;
            margin-bottom: 25px;
        }
        .meta-strip td {
            padding: 12px 16px;
            vertical-align: top;
            border-right: 1px solid #edf2f7;
        }
        .meta-strip td:last-child {
            border-right: 0;
        }
        .meta-label {
            font-size: 8.5px;
            font-weight: bold;
            color: #333333;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 4px;
        }
        .meta-value {
            font-size: 11.5px;
            font-weight: 700;
            color: #000000;
        }
        
        /* Executive Summary Box */
        .summary-container {
            border-left: 3px solid #135266;
            padding: 2px 0 2px 18px;
            margin-bottom: 30px;
        }
        .summary-header {
            font-size: 10px;
            font-weight: bold;
            color: #135266;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }
        .summary-body {
            font-size: 12px;
            color: #000000;
            line-height: 1.6;
        }
        
        /* Section Dividers */
        .section-header {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        .section-header td {
            padding: 0;
            vertical-align: middle;
        }
        .section-title {
            font-size: 11px;
            font-weight: 800;
            color: #135266;
            text-transform: uppercase;
            letter-spacing: 1px;
            white-space: nowrap;
            padding-right: 15px;
        }
        .section-line {
            width: 100%;
            height: 1px;
            background-color: #edf2f7;
        }

        /* 3-Column and 2-Column Grid Layouts */
        .layout-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 15px 0px;
            margin-left: -15px;
            margin-right: -15px;
        }
        .layout-table td {
            padding: 0;
            vertical-align: top;
        }
        
        /* KPI Cards */
        .kpi-card {
            background-color: #ffffff;
            border: 1px solid #edf2f7;
            border-radius: 6px;
            padding: 16px;
        }
        .kpi-title {
            font-size: 8.5px;
            font-weight: bold;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-bottom: 1px solid #f7fafc;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        .kpi-row {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .kpi-row td {
            padding: 3px 0;
            font-size: 10.5px;
        }
        .kpi-label {
            color: #222222;
        }
        .kpi-val {
            font-weight: 700;
            color: #000000;
            text-align: right;
        }
        
        /* Score Indicator Badge */
        .score-badge {
            font-size: 13px;
            font-weight: 800;
            padding: 5px 12px;
            border-radius: 6px;
            text-align: center;
            display: inline-block;
        }
        .score-success {
            background-color: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .score-warning {
            background-color: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .score-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Status badges */
        .status-pill {
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 2px 7px;
            border-radius: 4px;
            display: inline-block;
        }
        .status-success {
            background-color: #f0fdf4;
            color: #166534;
        }
        .status-danger {
            background-color: #fef2f2;
            color: #991b1b;
        }
        .status-warning {
            background-color: #fffbeb;
            color: #92400e;
        }

        /* Table custom styling */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 20px;
        }
        .data-table th, .data-table td {
            padding: 10px 12px;
            text-align: left;
            vertical-align: middle;
            border-bottom: 1px solid #edf2f7;
        }
        .data-table th {
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            color: #a0aec0;
            letter-spacing: 0.8px;
            background-color: #fcfdfd;
        }
        .data-table td {
            font-size: 10.5px;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 9px;
            color: #a0aec0;
            border-top: 1px solid #edf2f7;
            padding-top: 15px;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <!-- Watermark Background -->
    <div class="watermark">
        ASPIRE HUB
    </div>

    <!-- Top Branding Line -->
    <div class="brand-accent-bar"></div>

    <!-- Header Block -->
    <table class="header-table">
        <tr>
            <td>
                <div class="brand-title">Website Audit & Maintenance</div>
                <div class="brand-subtitle">Monthly execution report for platform upgrades, performance logs & security shields</div>
            </td>
            <td align="right" style="width: 200px;">
                <table style="border-collapse: collapse; border: 0; float: right;">
                    <tr>
                        <td align="right" style="padding-bottom: 8px;">
                            <span style="font-size: 16px; font-weight: 800; color: #135266; letter-spacing: 0.5px; text-transform: uppercase;">Aspire Hub</span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <span class="report-tag">REPORT #{{ $report->id }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="meta-strip">
        <tr>
            <td width="33%">
                <div class="meta-label">Name</div>
                <div class="meta-value">{{ $report->client->user->name }}</div>
            </td>
            <td width="33%">
                <div class="meta-label">Website</div>
                <div class="meta-value" style="color: #135266;">{{ rtrim(str_replace(['http://', 'https://'], '', $report->website->url), '/') }}</div>
            </td>
            <td width="33%">
                <div class="meta-label">Month Period</div>
                <div class="meta-value">{{ $report->maintenance_month }}</div>
            </td>
        </tr>
    </table>

    <!-- Executive Summary Box -->
    <div class="summary-container">
        <div class="summary-header">Executive Summary & Activity</div>
        <div class="summary-body">
            {!! nl2br(e($report->client_summary ?: 'All systems evaluated successfully. Platforms are secure, patched, and optimized.')) !!}
        </div>
    </div>

    <!-- Section 1 -->
    <table class="section-header">
        <tr>
            <td class="section-title">01. Core Systems & Upgrades</td>
            <td style="width: 100%;"><div class="section-line"></div></td>
        </tr>
    </table>

    <!-- 3-Column KPI layout -->
    <table class="layout-table">
        <tr>
            <td width="33.33%">
                <div class="kpi-card">
                    <div class="kpi-title">WordPress Engine</div>
                    <table class="kpi-row">
                        <tr>
                            <td class="kpi-label">Current Version</td>
                            <td class="kpi-val">{{ $report->wp_version_current ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="kpi-label">Target Release</td>
                            <td class="kpi-val">{{ $report->wp_version_latest ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="kpi-label" style="padding-top: 6px;">Upgrade Executed</td>
                            <td class="kpi-val" style="padding-top: 6px;">
                                <span class="status-pill {{ $report->wp_updated ? 'status-success' : 'status-danger' }}">
                                    {{ $report->wp_updated ? 'Yes' : 'No' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                    @if($report->wp_notes)
                        <div style="margin-top: 10px; padding-top: 8px; border-top: 1px dashed #edf2f7; font-size: 9px; color:#718096; line-height: 1.3;">
                            {{ $report->wp_notes }}
                        </div>
                    @endif
                </div>
            </td>
            <td width="33.33%">
                <div class="kpi-card">
                    <div class="kpi-title">PHP Environment</div>
                    <table class="kpi-row">
                        <tr>
                            <td class="kpi-label">Active Version</td>
                            <td class="kpi-val">{{ $report->php_version_current ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="kpi-label">Recommended</td>
                            <td class="kpi-val">{{ $report->php_version_recommended ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="kpi-label" style="padding-top: 6px;">Patch Executed</td>
                            <td class="kpi-val" style="padding-top: 6px;">
                                <span class="status-pill {{ $report->php_updated ? 'status-success' : 'status-danger' }}">
                                    {{ $report->php_updated ? 'Yes' : 'No' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                    @if($report->php_notes)
                        <div style="margin-top: 10px; padding-top: 8px; border-top: 1px dashed #edf2f7; font-size: 9px; color:#718096; line-height: 1.3;">
                            {{ $report->php_notes }}
                        </div>
                    @endif
                </div>
            </td>
            <td width="33.33%">
                <div class="kpi-card">
                    <div class="kpi-title">Active Theme</div>
                    <table class="kpi-row">
                        <tr>
                            <td class="kpi-label" style="max-width: 55px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Theme Name</td>
                            <td class="kpi-val" style="max-width: 55px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $report->theme_name ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="kpi-label">Active Version</td>
                            <td class="kpi-val">{{ $report->theme_version ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="kpi-label" style="padding-top: 6px;">Patch Executed</td>
                            <td class="kpi-val" style="padding-top: 6px;">
                                <span class="status-pill {{ $report->theme_updated ? 'status-success' : 'status-danger' }}">
                                    {{ $report->theme_updated ? 'Yes' : 'No' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                    @if($report->theme_notes)
                        <div style="margin-top: 10px; padding-top: 8px; border-top: 1px dashed #edf2f7; font-size: 9px; color:#718096; line-height: 1.3;">
                            {{ $report->theme_notes }}
                        </div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Section 2 -->
    <table class="section-header">
        <tr>
            <td class="section-title">02. Performance & Diagnostics</td>
            <td style="width: 100%;"><div class="section-line"></div></td>
        </tr>
    </table>

    <!-- 2-Column Grid -->
    <table class="layout-table">
        <tr>
            <td width="50%">
                <div class="kpi-card">
                    <div class="kpi-title">Google PageSpeed Scores</div>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 10px 0; vertical-align: middle;">
                                <div style="font-weight: 700; font-size: 11px; color: #2d3748;">Desktop Experience</div>
                                <div style="font-size: 9.5px; color: #a0aec0; margin-top: 2px;">Core vitals on desktop browsers</div>
                            </td>
                            <td align="right" style="padding: 10px 0; vertical-align: middle; width: 55px;">
                                @php $ds = $report->performance_desktop; @endphp
                                <span class="score-badge {{ $ds >= 90 ? 'score-success' : ($ds >= 50 ? 'score-warning' : 'score-danger') }}">
                                    {{ $ds }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; vertical-align: middle; border-top: 1px solid #f7fafc;">
                                <div style="font-weight: 700; font-size: 11px; color: #2d3748;">Mobile Experience</div>
                                <div style="font-size: 9.5px; color: #a0aec0; margin-top: 2px;">Optimization index for phone browsers</div>
                            </td>
                            <td align="right" style="padding: 10px 0; vertical-align: middle; width: 55px; border-top: 1px solid #f7fafc;">
                                @php $ms = $report->performance_mobile; @endphp
                                <span class="score-badge {{ $ms >= 90 ? 'score-success' : ($ms >= 50 ? 'score-warning' : 'score-danger') }}">
                                    {{ $ms }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 0 0 0; vertical-align: middle; border-top: 1px solid #f7fafc;">
                                <div style="font-weight: 700; font-size: 11px; color: #2d3748;">Core Web Vitals</div>
                            </td>
                            <td align="right" style="padding: 12px 0 0 0; vertical-align: middle;">
                                <span class="status-pill {{ strtolower($report->performance_core_web_vitals) === 'passed' ? 'status-success' : 'status-danger' }}">
                                    {{ $report->performance_core_web_vitals ?: 'N/A' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                    @if($report->performance_notes)
                        <div style="margin-top: 12px; padding: 10px; background-color: #f7fafc; border-radius: 4px; font-size: 9.5px; color: #718096; line-height: 1.4;">
                            <strong>Optimizer Notes:</strong><br>{{ $report->performance_notes }}
                        </div>
                    @endif
                </div>
            </td>
            <td width="50%">
                <div class="kpi-card">
                    <div class="kpi-title">System Health Diagnostics</div>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 10px 0; vertical-align: middle;">
                                <div style="font-weight: 700; font-size: 11px; color: #2d3748;">Site Health Index</div>
                                <div style="font-size: 9.5px; color: #a0aec0; margin-top: 2px;">Standard WordPress system health status</div>
                            </td>
                            <td align="right" style="padding: 10px 0; vertical-align: middle; width: 55px;">
                                @php $hs = $report->health_score; @endphp
                                <span class="score-badge {{ $hs >= 85 ? 'score-success' : ($hs >= 60 ? 'score-warning' : 'score-danger') }}">
                                    {{ $hs }}%
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 0; border-top: 1px solid #f7fafc; color: #718096;">Critical Issues Addressed</td>
                            <td align="right" style="padding: 12px 0; font-weight: 700; color: #e53e3e; border-top: 1px solid #f7fafc;">{{ $report->health_critical_issues }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 0 0 0; border-top: 1px solid #f7fafc; color: #718096;">System Alerts & Warnings</td>
                            <td align="right" style="padding: 12px 0 0 0; font-weight: 700; color: #dd6b20; border-top: 1px solid #f7fafc;">{{ $report->health_warnings }}</td>
                        </tr>
                    </table>
                    @if($report->health_notes)
                        <div style="margin-top: 12px; padding: 10px; background-color: #f7fafc; border-radius: 4px; font-size: 9.5px; color: #718096; line-height: 1.4;">
                            <strong>Diagnostics Feedback:</strong><br>{{ $report->health_notes }}
                        </div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Section 3 -->
    <table class="section-header">
        <tr>
            <td class="section-title">03. Security Integrity</td>
            <td style="width: 100%;"><div class="section-line"></div></td>
        </tr>
    </table>

    <div class="kpi-card" style="margin-bottom: 25px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td width="25%" style="border-right: 1px solid #edf2f7; padding: 4px 12px 4px 0;">
                    <div style="font-size: 8px; font-weight: bold; color: #a0aec0; text-transform: uppercase; letter-spacing: 0.5px;">Malware Scan</div>
                    <div style="font-size: 11.5px; font-weight: 700; color: #2d3748; margin-top: 4px; text-transform: uppercase;">
                        @if(strtolower($report->security_malware_scan) === 'clean' || strtolower($report->security_malware_scan) === 'passed')
                            <span style="color:#166534;">CLEAN</span>
                        @else
                            {{ $report->security_malware_scan }}
                        @endif
                    </div>
                </td>
                <td width="25%" style="border-right: 1px solid #edf2f7; padding: 4px 12px;">
                    <div style="font-size: 8px; font-weight: bold; color: #a0aec0; text-transform: uppercase; letter-spacing: 0.5px;">Firewall Protection</div>
                    <div style="font-size: 11.5px; font-weight: 700; color: #2d3748; margin-top: 4px; text-transform: uppercase;">
                        @if(strtolower($report->security_firewall_status) === 'active' || strtolower($report->security_firewall_status) === 'enabled')
                            <span style="color:#166534;">ENABLED</span>
                        @else
                            {{ $report->security_firewall_status }}
                        @endif
                    </div>
                </td>
                <td width="25%" style="border-right: 1px solid #edf2f7; padding: 4px 12px;">
                    <div style="font-size: 8px; font-weight: bold; color: #a0aec0; text-transform: uppercase; letter-spacing: 0.5px;">SSL Certificate</div>
                    <div style="font-size: 11.5px; font-weight: 700; color: #2d3748; margin-top: 4px; text-transform: uppercase;">
                        @if(strtolower($report->security_ssl_status) === 'valid' || strtolower($report->security_ssl_status) === 'active')
                            <span style="color:#166534;">SECURE</span>
                        @else
                            {{ $report->security_ssl_status }}
                        @endif
                    </div>
                </td>
                <td width="25%" style="padding: 4px 0 4px 12px;">
                    <div style="font-size: 8px; font-weight: bold; color: #a0aec0; text-transform: uppercase; letter-spacing: 0.5px;">Security Health</div>
                    <div style="font-size: 11.5px; font-weight: 700; color: #2d3748; margin-top: 4px; text-transform: uppercase;">
                        <span class="status-pill status-success">{{ $report->security_health ?: 'Excellent' }}</span>
                    </div>
                </td>
            </tr>
        </table>
        @if($report->security_notes)
            <div style="margin-top: 12px; padding-top: 10px; border-top: 1px dashed #edf2f7; font-size: 9.5px; color: #718096; line-height: 1.4;">
                <strong>Security Notes:</strong> {{ $report->security_notes }}
            </div>
        @endif
    </div>

    <!-- Section 4 -->
    @if(!$report->plugins->isEmpty())
        <div class="page-break"></div>
        <table class="section-header">
            <tr>
                <td class="section-title">04. Plugins Upgrade Log</td>
                <td style="width: 100%;"><div class="section-line"></div></td>
            </tr>
        </table>
        
        <table class="data-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th width="35%">Plugin Name</th>
                    <th width="15%">Previous</th>
                    <th width="15%">New Version</th>
                    <th width="15%">Status</th>
                    <th width="20%">Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report->plugins as $plugin)
                    <tr>
                        <td style="font-weight: 700; color: #2d3748;">{{ $plugin->plugin_name }}</td>
                        <td style="color:#718096; font-family: monospace;">{{ $plugin->old_version ?: '—' }}</td>
                        <td style="color:#2d3748; font-family: monospace; font-weight: 600;">{{ $plugin->new_version ?: '—' }}</td>
                        <td>
                            <span class="status-pill {{ $plugin->status === 'updated' ? 'status-success' : '' }} {{ $plugin->status === 'failed' ? 'status-danger' : '' }} {{ $plugin->status === 'license_required' ? 'status-warning' : '' }}">
                                {{ $plugin->status }}
                            </span>
                        </td>
                        <td style="font-size: 10px; color: #718096;">{{ $plugin->notes ?: 'Upgraded successfully.' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Attachments -->
    @if($report->attachments_visible_to_client && $report->attachments->isNotEmpty())
        <div class="page-break"></div>
        <table class="section-header">
            <tr>
                <td class="section-title">05. Audit Attachments</td>
                <td style="width: 100%;"><div class="section-line"></div></td>
            </tr>
        </table>
        
        <div style="margin-top: 15px;">
            @foreach($report->attachments as $att)
                @php
                    $extension = pathinfo($att->file_path, PATHINFO_EXTENSION);
                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                    $fullPath = storage_path('app/public/' . $att->file_path);
                @endphp
                <div style="margin-bottom: 25px; page-break-inside: avoid;">
                    <div style="font-size: 12px; font-weight: bold; color: #2d3748; margin-bottom: 8px;">
                        {{ $att->file_name }}
                    </div>
                    @if($isImage && file_exists($fullPath))
                        <div style="border: 1px solid #edf2f7; border-radius: 6px; overflow: hidden; background: #ffffff; text-align: center; padding: 10px;">
                            <img src="{{ $fullPath }}" style="max-width: 100%; max-height: 400px; object-fit: contain; border-radius: 4px;" />
                        </div>
                    @else
                        <div style="padding: 12px; background: #f7fafc; border: 1px solid #edf2f7; border-radius: 6px; font-size: 11px; color: #718096;">
                            Attachment: <strong>{{ $att->file_name }}</strong> (Available for download in Aspire Hub portal)
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <div class="footer">
        For any enquiries, please email support@aspiredigitalsolutions.com
    </div>

</body>
</html>
