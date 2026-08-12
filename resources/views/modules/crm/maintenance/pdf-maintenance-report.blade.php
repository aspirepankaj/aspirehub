<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Maintenance Report #{{ $report->id }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        
        /* Header Block - Solid dark background for reliable DomPDF rendering */
        .header-container {
            background-color: #0f172a;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 20px 24px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: 0;
        }
        .header-table td {
            padding: 0;
            border: 0;
            vertical-align: middle;
        }
        .header-title {
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .header-subtitle {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 4px;
        }
        
        /* Meta Data Grid Container */
        .meta-container {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background-color: #f8fafc;
            margin-bottom: 20px;
            padding: 12px 16px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            border: 0;
        }
        .meta-table td {
            padding: 4px 8px;
            border: 0;
            vertical-align: top;
        }
        .meta-label {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
        }
        .meta-value {
            font-size: 11.5px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 2px;
        }
        
        /* Executive Summary block */
        .summary-box {
            background-color: #f5f3ff;
            border-left: 4px solid #6d28d9;
            padding: 14px 18px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .summary-title {
            font-weight: bold;
            color: #6d28d9;
            margin-bottom: 6px;
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .summary-content {
            font-size: 11.5px;
            color: #4c1d95;
            line-height: 1.6;
        }

        /* Section Headings */
        .section-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #4f46e5;
            border-bottom: 1.5px solid #e2e8f0;
            padding-bottom: 4px;
            margin-top: 25px;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }
        
        /* Grid Tables */
        .card-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .card-table td {
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
            background-color: #ffffff;
        }
        .card-table td strong {
            color: #475569;
            font-weight: 600;
        }
        .bg-gray {
            background-color: #f8fafc !important;
        }
        
        /* Plugin list style */
        .plugin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 15px;
        }
        .plugin-table th, .plugin-table td {
            border: 1px solid #e2e8f0;
            padding: 8px 10px;
            text-align: left;
            vertical-align: middle;
        }
        .plugin-table th {
            background-color: #f1f5f9;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            color: #475569;
            letter-spacing: 0.5px;
        }
        
        /* Status Badges */
        .badge {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 2px 6px;
            border-radius: 4px;
        }
        .badge-success {
            background-color: #dcfce7;
            color: #15803d;
        }
        .badge-failed {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #b45309;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .footer-note {
            margin-top: 40px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
        }
    </style>
</head>
<body>

    <!-- Header Block -->
    <div class="header-container">
        <table class="header-table">
            <tr>
                <td>
                    <div class="header-title">Website Maintenance Report</div>
                    <div class="header-subtitle">Monthly site security, performance & health optimization audit</div>
                </td>
                <td align="right" style="width: 120px;">
                    <img src="{{ public_path('aspire_logo.png') }}" style="height: 24px; width: auto; display: block; margin-bottom: 4px;" />
                    <div style="font-size: 13px; font-weight: bold; color: #ffffff;">#{{ $report->id }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Meta Details -->
    <div class="meta-container">
        <table class="meta-table">
            <tr>
                <td width="33%">
                    <div class="meta-label">Name</div>
                    <div class="meta-value">{{ $report->client->user->name }}</div>
                </td>
                <td width="33%">
                    <div class="meta-label">Website Domain</div>
                    <div class="meta-value">{{ $report->website->url }}</div>
                </td>
                <td width="33%">
                    <div class="meta-label">Reporting Month</div>
                    <div class="meta-value">{{ $report->maintenance_month }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Client Facing Summary -->
    <div class="summary-box">
        <div class="summary-title">Executive Summary & Work Completed</div>
        <div class="summary-content">
            {!! nl2br(e($report->client_summary ?: 'Website is running normally. All security and platform cores verified successfully.')) !!}
        </div>
    </div>

    <!-- Core Systems Status -->
    <div class="section-title">1. Core Platform Upgrades</div>
    <table class="card-table">
        <tr>
            <td width="33%" class="bg-gray"><span class="meta-label">WordPress Core</span></td>
            <td width="33%" class="bg-gray"><span class="meta-label">PHP Environment</span></td>
            <td width="33%" class="bg-gray"><span class="meta-label">Active Theme</span></td>
        </tr>
        <tr>
            <td>
                <strong>Current:</strong> {{ $report->wp_version_current ?: 'N/A' }}<br>
                <strong>Latest:</strong> {{ $report->wp_version_latest ?: 'N/A' }}<br>
                <strong>Updated:</strong> {{ $report->wp_updated ? 'Yes' : 'No' }}
            </td>
            <td>
                <strong>Current:</strong> {{ $report->php_version_current ?: 'N/A' }}<br>
                <strong>Recommended:</strong> {{ $report->php_version_recommended ?: 'N/A' }}<br>
                <strong>Updated:</strong> {{ $report->php_updated ? 'Yes' : 'No' }}
            </td>
            <td>
                <strong>Theme:</strong> {{ $report->theme_name ?: 'N/A' }}<br>
                <strong>Version:</strong> {{ $report->theme_version ?: 'N/A' }}<br>
                <strong>Updated:</strong> {{ $report->theme_updated ? 'Yes' : 'No' }}
            </td>
        </tr>
        @if($report->wp_notes || $report->php_notes || $report->theme_notes)
        <tr>
            <td><small style="font-size: 9.5px; color: #64748b; line-height: 1.3;">{{ $report->wp_notes }}</small></td>
            <td><small style="font-size: 9.5px; color: #64748b; line-height: 1.3;">{{ $report->php_notes }}</small></td>
            <td><small style="font-size: 9.5px; color: #64748b; line-height: 1.3;">{{ $report->theme_notes }}</small></td>
        </tr>
        @endif
    </table>

    <!-- Health & Performance metrics -->
    <div class="section-title">2. Performance & Health Audit</div>
    <table class="card-table">
        <tr>
            <td width="50%" class="bg-gray"><span class="meta-label">Google PageSpeed Scores</span></td>
            <td width="50%" class="bg-gray"><span class="meta-label">System Health Audit</span></td>
        </tr>
        <tr>
            <td>
                <strong>Desktop Score:</strong> {{ $report->performance_desktop }}/100<br>
                <strong>Mobile Score:</strong> {{ $report->performance_mobile }}/100<br>
                <strong>Core Web Vitals:</strong> <span style="text-transform: uppercase;">{{ $report->performance_core_web_vitals }}</span>
            </td>
            <td>
                <strong>Site Health Score:</strong> {{ $report->health_score }}/100<br>
                <strong>Critical issues resolved:</strong> {{ $report->health_critical_issues }}<br>
                <strong>Warnings:</strong> {{ $report->health_warnings }}
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- Security Profile -->
    <div class="section-title">3. Security Profile</div>
    <table class="card-table">
        <tr>
            <td class="bg-gray"><span class="meta-label">Security Shield Checks</span></td>
        </tr>
        <tr>
            <td>
                <strong>Malware Scan:</strong> <span style="text-transform: uppercase;">{{ $report->security_malware_scan }}</span><br>
                <strong>Firewall Shield:</strong> <span style="text-transform: uppercase;">{{ $report->security_firewall_status }}</span><br>
                <strong>SSL Certificate:</strong> <span style="text-transform: uppercase;">{{ $report->security_ssl_status }}</span><br>
                <strong>Security Health:</strong> <span style="text-transform: uppercase;">{{ $report->security_health ?: 'Excellent' }}</span>
            </td>
        </tr>
        @if($report->security_notes)
        <tr>
            <td><small style="font-size: 9.5px; color: #64748b; line-height: 1.3;">{{ $report->security_notes }}</small></td>
        </tr>
        @endif
    </table>

    <!-- Plugin list -->
    <div class="section-title">4. Plugins Update Log</div>
    @if($report->plugins->isEmpty())
        <p style="color: #64748b; font-style: italic; padding-left: 5px;">No plugin updates performed during this cycle.</p>
    @else
        <table class="plugin-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th width="30%">Plugin Name</th>
                    <th width="15%">Previous</th>
                    <th width="15%">New</th>
                    <th width="15%">Status</th>
                    <th width="25%">Upgrade Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report->plugins as $plugin)
                    <tr>
                        <td style="font-weight: bold; color: #0f172a;">{{ $plugin->plugin_name }}</td>
                        <td>{{ $plugin->old_version ?: '—' }}</td>
                        <td>{{ $plugin->new_version ?: '—' }}</td>
                        <td>
                            <span class="badge {{ $plugin->status === 'updated' ? 'badge-success' : '' }} {{ $plugin->status === 'failed' ? 'badge-failed' : '' }} {{ $plugin->status === 'license_required' ? 'badge-warning' : '' }}">
                                {{ $plugin->status }}
                            </span>
                        </td>
                        <td style="font-size: 10px; color: #475569;">{{ $plugin->notes ?: 'Upgraded successfully.' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($report->attachments_visible_to_client && $report->attachments->isNotEmpty())
        <div class="page-break"></div>
        <div class="section-title">5. Report Attachments</div>
        <div style="margin-top: 15px;">
            @foreach($report->attachments as $att)
                @php
                    $extension = pathinfo($att->file_path, PATHINFO_EXTENSION);
                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                    $fullPath = storage_path('app/public/' . $att->file_path);
                @endphp
                <div style="margin-bottom: 25px; page-break-inside: avoid;">
                    <div style="font-size: 12px; font-weight: bold; color: #1e293b; margin-bottom: 8px;">
                        {{ $att->file_name }}
                    </div>
                    @if($isImage && file_exists($fullPath))
                        <div style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; background: #ffffff; text-align: center; padding: 10px;">
                            <img src="{{ $fullPath }}" style="max-width: 100%; max-height: 400px; object-fit: contain; border-radius: 4px;" />
                        </div>
                    @else
                        <div style="padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 11px; color: #475569;">
                            Attachment: <strong>{{ $att->file_name }}</strong> (Available for download in Aspire Hub portal)
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <div class="footer-note">
        For any enquiries, please email support@aspiredigitalsolutions.com
    </div>

</body>
</html>
