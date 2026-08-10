<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Website Maintenance Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
            padding: 32px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 8px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 32px;
        }
        .content p {
            font-size: 15px;
            line-height: 1.6;
            margin: 0 0 20px 0;
        }
        .metrics-card {
            background-color: #f1f5f9;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .metrics-grid {
            display: table;
            width: 100%;
        }
        .metric-col {
            display: table-cell;
            width: 33.33%;
            text-align: center;
        }
        .metric-val {
            font-size: 24px;
            font-weight: 800;
            color: #4f46e5;
        }
        .metric-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-top: 4px;
        }
        .summary-box {
            border-left: 4px solid #06b6d4;
            background-color: #ecfeff;
            padding: 16px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 24px;
        }
        .summary-title {
            font-weight: bold;
            font-size: 14px;
            color: #0e7490;
            margin-bottom: 4px;
        }
        .summary-text {
            font-size: 13.5px;
            color: #164e63;
            margin: 0;
            line-height: 1.5;
        }
        .btn-wrapper {
            text-align: center;
            margin: 32px 0 16px 0;
        }
        .btn {
            background-color: #4f46e5;
            color: #ffffff !important;
            padding: 12px 28px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            display: inline-block;
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Website Maintenance Report</h1>
            <p>{{ $report->maintenance_month }} &bull; {{ $report->website->site_name }}</p>
        </div>
        <div class="content">
            <p>Hello {{ $report->client->user->name }},</p>
            <p>We have successfully completed the website maintenance cycle for <strong>{{ $report->website->site_name }}</strong> for the month of <strong>{{ $report->maintenance_month }}</strong>. All core modules, PHP environments, database configurations, and active plugins have been audited and updated.</p>

            <div class="metrics-card">
                <div class="metrics-grid">
                    <div class="metric-col" style="border-right: 1px solid #cbd5e1;">
                        <div class="metric-val">{{ $report->health_score }}%</div>
                        <div class="metric-label">Health Score</div>
                    </div>
                    <div class="metric-col" style="border-right: 1px solid #cbd5e1;">
                        <div class="metric-val">{{ $report->performance_desktop }}</div>
                        <div class="metric-label">Desktop Speed</div>
                    </div>
                    <div class="metric-col">
                        <div class="metric-val">{{ $report->performance_mobile }}</div>
                        <div class="metric-label">Mobile Speed</div>
                    </div>
                </div>
            </div>

            @if($report->client_summary)
                <div class="summary-box">
                    <div class="summary-title">Summary of Updates</div>
                    <p class="summary-text">{{ $report->client_summary }}</p>
                </div>
            @endif

            <p>Please find the fully detailed PDF report attached to this email. It contains a complete log of all updated plugins, backup targets, and security scanning profiles.</p>

            <div class="btn-wrapper">
                <a href="{{ $report->website->url }}" class="btn" target="_blank">Visit Your Website</a>
            </div>
        </div>
        <div class="footer">
            <p>Sent by <strong>Aspire Hub</strong> Portal System.</p>
            <p>&copy; {{ date('Y') }} Aspire Hub. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
