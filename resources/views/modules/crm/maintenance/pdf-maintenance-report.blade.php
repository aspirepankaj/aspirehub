<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Maintenance Report #{{ $report->id }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            font-size: 13px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .watermark {
            position: fixed;
            top: 40%;
            left: 0;
            width: 100%;
            text-align: center;
            opacity: 0.07;
            z-index: -1000;
            transform: rotate(-30deg);
            font-size: 75px;
            font-weight: bold;
            color: #4f46e5;
            letter-spacing: 8px;
        }
        .header-table {
            width: 100%;
            background-color: #1e1b4b; /* Solid dark navy background */
            margin-bottom: 25px;
            border-radius: 6px;
        }
        .header-title {
            font-size: 20px;
            font-weight: bold;
            color: #ffffff; /* White title */
            margin: 0;
            letter-spacing: -0.5px;
        }
        .header-subtitle {
            font-size: 10px;
            color: #a5b4fc; /* Light indigo subtitle */
            margin-top: 3px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
        }
        .meta-label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
        }
        .meta-value {
            font-size: 12px;
            font-weight: bold;
            color: #1e293b;
            margin-top: 2px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #4f46e5;
            border-bottom: 1.5px solid #e2e8f0;
            padding-bottom: 4px;
            margin-top: 25px;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }
        .card-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .card-table td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .bg-gray {
            background-color: #f8fafc;
        }
        .plugin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .plugin-table th, .plugin-table td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: left;
        }
        .plugin-table th {
            background-color: #f1f5f9;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #475569;
            letter-spacing: 0.5px;
        }
        .badge {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 2.5px 5px;
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
        .summary-box {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 12px 15px;
            margin-top: 15px;
            border-radius: 4px;
        }
        .summary-title {
            font-weight: bold;
            color: #1d4ed8;
            margin-bottom: 5px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <!-- Background Watermark -->
    <div class="watermark">ASPIRE HUB</div>

    <!-- Header Section -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td width="70%" style="vertical-align: middle; padding: 15px 0 15px 20px;">
                <div class="header-title">Website Maintenance Report</div>
                <div class="header-subtitle">Monthly site security, performance & health optimization audit</div>
            </td>
            <td width="30%" align="right" style="vertical-align: middle; padding: 15px 20px 15px 0;">
                <img src="{{ public_path('aspire_logo.png') }}" style="height: 30px; width: auto; display: block; margin-bottom: 4px;" />
                <div style="font-size: 10px; font-weight: bold; color: #a5b4fc;">REPORT ID: #{{ $report->id }}</div>
            </td>
        </tr>
    </table>

    <!-- Meta Details -->
    <table class="meta-table" cellpadding="0" cellspacing="0">
        <tr>
            <td width="33%">
                <div class="meta-label">Client Name</div>
                <div class="meta-value">{{ $report->client->user->name }}</div>
            </td>
            <td width="33%">
                <div class="meta-label">Website Domain</div>
                <div class="meta-value">{{ $report->website->url }}</div>
            </td>
            <td width="33%">
                <div class="meta-label">Reporting Period</div>
                <div class="meta-value">{{ $report->maintenance_month }}</div>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 10px;">
                <div class="meta-label">Audit Date</div>
                <div class="meta-value">{{ $report->maintenance_date->format('d M, Y') }}</div>
            </td>
            <td style="padding-top: 10px;">
                <div class="meta-label">Engineer Assigned</div>
                <div class="meta-value">{{ $report->developer->name }}</div>
            </td>
            <td style="padding-top: 10px;">
                <div class="meta-label">Status</div>
                <div class="meta-value" style="color: #15803d; text-transform: uppercase;">{{ $report->status }}</div>
            </td>
        </tr>
    </table>

    <!-- Client Facing Summary -->
    <div class="summary-box">
        <div class="summary-title">Executive Summary & Work Completed</div>
        <div style="font-size: 12px; color: #1e3a8a; line-height: 1.6;">
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
            <td><small style="font-size: 10px; color: #64748b;">{{ $report->wp_notes }}</small></td>
            <td><small style="font-size: 10px; color: #64748b;">{{ $report->php_notes }}</small></td>
            <td><small style="font-size: 10px; color: #64748b;">{{ $report->theme_notes }}</small></td>
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

    <!-- Security & Backups -->
    <div class="section-title">3. Security Profile & Backups</div>
    <table class="card-table">
        <tr>
            <td width="50%" class="bg-gray"><span class="meta-label">Security Shield Checks</span></td>
            <td width="50%" class="bg-gray"><span class="meta-label">Disaster Recovery Backups</span></td>
        </tr>
        <tr>
            <td>
                <strong>Malware Scan:</strong> <span style="text-transform: uppercase;">{{ $report->security_malware_scan }}</span><br>
                <strong>Firewall Shield:</strong> <span style="text-transform: uppercase;">{{ $report->security_firewall_status }}</span><br>
                <strong>SSL Certificate:</strong> <span style="text-transform: uppercase;">{{ $report->security_ssl_status }}</span><br>
                <strong>Security Health:</strong> <span style="text-transform: uppercase;">{{ $report->security_health ?: 'Excellent' }}</span>
            </td>
            <td>
                <strong>Backup Status:</strong> {{ $report->backup_completed ? 'Completed' : 'Failed' }}<br>
                <strong>Backup Date:</strong> {{ $report->backup_date ? $report->backup_date->format('d M, Y') : '—' }}<br>
                <strong>Cloud Destination:</strong> {{ $report->backup_location }}
            </td>
        </tr>
        @if($report->security_notes || $report->backup_notes)
        <tr>
            <td><small style="font-size: 10px; color: #64748b;">{{ $report->security_notes }}</small></td>
            <td><small style="font-size: 10px; color: #64748b;">{{ $report->backup_notes }}</small></td>
        </tr>
        @endif
    </table>

    <!-- Plugin list -->
    <div class="section-title">4. Core Plugins Update Log</div>
    @if($report->plugins->isEmpty())
        <p style="color: #777777;">No plugin updates performed during this cycle.</p>
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
                        <td style="font-weight: bold;">{{ $plugin->plugin_name }}</td>
                        <td>{{ $plugin->old_version ?: '—' }}</td>
                        <td>{{ $plugin->new_version ?: '—' }}</td>
                        <td>
                            <span class="badge {{ $plugin->status === 'updated' ? 'badge-success' : '' }} {{ $plugin->status === 'failed' ? 'badge-failed' : '' }} {{ $plugin->status === 'license_required' ? 'badge-warning' : '' }}">
                                {{ $plugin->status }}
                            </span>
                        </td>
                        <td style="font-size: 11px; color: #555555;">{{ $plugin->notes ?: 'Upgraded successfully.' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Support Cycle Summary -->
    <div class="section-title">5. Support Cycle Activity</div>
    <table class="card-table">
        <tr>
            <td width="33%" class="bg-gray"><span class="meta-label">Support Tickets Resolved</span></td>
            <td width="33%" class="bg-gray"><span class="meta-label">Time Logged</span></td>
            <td width="33%" class="bg-gray"><span class="meta-label">Cycle Completion</span></td>
        </tr>
        <tr>
            <td>
                <strong>Resolved:</strong> {{ $report->support_tickets_completed }}<br>
                <strong>Pending:</strong> {{ $report->support_tickets_pending }}
            </td>
            <td>
                <strong>Hours spent:</strong> {{ $report->support_time_spent ?: '—' }}
            </td>
            <td>
                <strong>Date:</strong> {{ $report->support_completion_date ? $report->support_completion_date->format('d M, Y') : '—' }}
            </td>
        </tr>
        @if($report->support_work_summary)
        <tr>
            <td colspan="3">
                <div class="meta-label">Activity Summary</div>
                <div style="font-size: 11px; color: #475569; margin-top: 4px;">
                    {!! nl2br(e($report->support_work_summary)) !!}
                </div>
            </td>
        </tr>
        @endif
    </table>

    <div style="margin-top: 50px; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px;">
        This report is generated dynamically by Aspire Hub Maintenance portal. For any enquiries, please email support.
    </div>

</body>
</html>
