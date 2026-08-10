<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Maintenance Report — Aspire Hub</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f0f4ff;
            color: #334155;
            -webkit-font-smoothing: antialiased;
            padding: 40px 16px;
        }
        .wrapper {
            max-width: 620px;
            margin: 0 auto;
        }
        /* Brand header above card */
        .brand-top {
            text-align: center;
            padding-bottom: 24px;
        }
        .brand-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6366f1, #ec4899);
            color: #fff;
            font-size: 20px;
            font-weight: 900;
            letter-spacing: -1px;
            margin-bottom: 8px;
        }
        .brand-name {
            display: block;
            font-size: 16px;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.3px;
        }
        /* Main card */
        .card {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(99, 102, 241, 0.10), 0 2px 8px rgba(0,0,0,0.06);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        /* Gradient header */
        .card-header {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #06b6d4 100%);
            padding: 40px 40px 32px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .card-header::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 140px; height: 140px;
            background: rgba(255,255,255,0.07);
            border-radius: 50%;
        }
        .card-header::after {
            content: '';
            position: absolute;
            bottom: -50px; left: -30px;
            width: 110px; height: 110px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .header-badge {
            display: inline-block;
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.3);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 4px 14px;
            border-radius: 20px;
            margin-bottom: 16px;
        }
        .card-header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 900;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }
        .card-header .site-name {
            color: rgba(255,255,255,0.9);
            font-size: 14px;
            font-weight: 600;
        }
        /* Card body */
        .card-body {
            padding: 36px 40px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
        }
        .body-text {
            font-size: 14.5px;
            line-height: 1.65;
            color: #475569;
            margin-bottom: 28px;
        }
        /* Scores section */
        .scores-label {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 14px;
        }
        .scores-grid {
            display: table;
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 28px;
        }
        .score-cell {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 24px 12px;
            vertical-align: middle;
        }
        .score-cell:not(:last-child) {
            border-right: 1px solid #f1f5f9;
        }
        .score-cell:first-child { border-radius: 16px 0 0 16px; }
        .score-cell:last-child  { border-radius: 0 16px 16px 0; }
        .score-value {
            font-size: 36px;
            font-weight: 900;
            letter-spacing: -2px;
            line-height: 1;
            margin-bottom: 6px;
        }
        .score-value.health   { color: #6366f1; }
        .score-value.perf     { color: #06b6d4; }
        .score-value.mobile   { color: #8b5cf6; }
        .score-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #94a3b8;
            font-weight: 700;
        }
        .score-sub {
            font-size: 10px;
            color: #cbd5e1;
            margin-top: 2px;
        }
        /* Activity chips */
        .activity-section {
            margin-bottom: 28px;
        }
        .activity-label {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 12px;
        }
        .chips-row {
            display: table;
            width: 100%;
        }
        .chip-cell {
            display: table-cell;
            width: 33.33%;
            padding: 0 5px;
            vertical-align: top;
        }
        .chip {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px;
            text-align: center;
        }
        .chip-icon {
            font-size: 20px;
            margin-bottom: 6px;
            display: block;
        }
        .chip-count {
            font-size: 20px;
            font-weight: 900;
            color: #1e293b;
            line-height: 1;
        }
        .chip-name {
            font-size: 10.5px;
            color: #94a3b8;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }
        /* Summary box */
        .summary-box {
            background: linear-gradient(135deg, #f0f4ff 0%, #faf5ff 100%);
            border: 1px solid #e0e7ff;
            border-left: 4px solid #6366f1;
            border-radius: 0 12px 12px 0;
            padding: 18px 20px;
            margin-bottom: 28px;
        }
        .summary-title {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #4338ca;
            margin-bottom: 8px;
        }
        .summary-text {
            font-size: 14px;
            color: #3730a3;
            line-height: 1.6;
        }
        /* Divider */
        .divider {
            height: 1px;
            background: #f1f5f9;
            margin: 24px 0;
        }
        /* CTA button */
        .btn-wrapper {
            text-align: center;
            margin-bottom: 8px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: #ffffff !important;
            text-decoration: none;
            font-size: 15px;
            font-weight: 800;
            padding: 14px 36px;
            border-radius: 12px;
            letter-spacing: -0.2px;
            box-shadow: 0 4px 16px rgba(99, 102, 241, 0.35);
        }
        .btn-sub {
            font-size: 12px;
            color: #94a3b8;
            text-align: center;
            margin-top: 10px;
        }
        /* Footer */
        .card-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 24px 40px;
            text-align: center;
        }
        .card-footer p {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 4px;
            line-height: 1.5;
        }
        /* Bottom note */
        .bottom-note {
            text-align: center;
            padding-top: 20px;
            font-size: 11.5px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Brand above card -->
        <div class="brand-top">
            <div class="brand-logo">AH</div>
            <span class="brand-name">Aspire Hub</span>
        </div>

        <div class="card">
            <!-- Gradient Header -->
            <div class="card-header">
                <div class="header-badge">Monthly Maintenance Report</div>
                <h1>{{ $report->maintenance_month }}</h1>
                <p class="site-name">{{ $report->website->site_name }} &bull; {{ $report->website->url }}</p>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <p class="greeting">Hello {{ $report->client->user->name }},</p>
                <p class="body-text">
                    We have successfully completed the website maintenance cycle for <strong>{{ $report->website->site_name }}</strong> for <strong>{{ $report->maintenance_month }}</strong>. All core modules, PHP environments, database configurations, and active plugins have been audited and updated. Here is your performance summary:
                </p>

                <!-- Score Grid -->
                <p class="scores-label">Performance Scores</p>
                <div class="scores-grid">
                    <div class="score-cell">
                        <div class="score-value health">{{ $report->health_score }}<span style="font-size:18px;">%</span></div>
                        <div class="score-label">Health</div>
                        <div class="score-sub">Overall Score</div>
                    </div>
                    <div class="score-cell">
                        <div class="score-value perf">{{ $report->performance_desktop }}</div>
                        <div class="score-label">Desktop Speed</div>
                        <div class="score-sub">PageSpeed Score</div>
                    </div>
                    <div class="score-cell">
                        <div class="score-value mobile">{{ $report->performance_mobile }}</div>
                        <div class="score-label">Mobile Speed</div>
                        <div class="score-sub">PageSpeed Score</div>
                    </div>
                </div>

                <!-- Activity chips -->
                @if($report->backups_count || $report->updates_count)
                <div class="activity-section">
                    <p class="activity-label">Activities This Cycle</p>
                    <div class="chips-row">
                        <div class="chip-cell">
                            <div class="chip">
                                <span class="chip-icon">💾</span>
                                <div class="chip-count">{{ $report->backups_count ?? 0 }}</div>
                                <div class="chip-name">Backups</div>
                            </div>
                        </div>
                        <div class="chip-cell">
                            <div class="chip">
                                <span class="chip-icon">🔄</span>
                                <div class="chip-count">{{ $report->updates_count ?? 0 }}</div>
                                <div class="chip-name">Updates</div>
                            </div>
                        </div>
                        <div class="chip-cell">
                            <div class="chip">
                                <span class="chip-icon">🔒</span>
                                <div class="chip-count">{{ $report->security_health ?? '—' }}</div>
                                <div class="chip-name">Security</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Summary -->
                @if($report->client_summary)
                <div class="summary-box">
                    <div class="summary-title">Summary of Updates</div>
                    <p class="summary-text">{{ $report->client_summary }}</p>
                </div>
                @endif

                <p class="body-text">
                    A fully detailed PDF report is attached to this email. It contains a complete log of all updated plugins, backup targets, and security scanning profiles.
                </p>

                <div class="divider"></div>

                <!-- CTA -->
                <div class="btn-wrapper">
                    <a href="{{ $report->website->url }}" class="btn" target="_blank">Visit Your Website &rarr;</a>
                </div>
                <p class="btn-sub">You can also view this report in your <a href="{{ url('/client/maintenance') }}" style="color:#6366f1; text-decoration:none; font-weight:700;">Client Portal</a></p>
            </div>

            <!-- Footer -->
            <div class="card-footer">
                <p><strong>Aspire Hub</strong> — Enterprise Website Maintenance Platform</p>
                <p>&copy; {{ date('Y') }} Aspire Hub. All rights reserved. This report was generated automatically.</p>
            </div>
        </div>

        <p class="bottom-note">You are receiving this because you are a registered client of Aspire Hub services.</p>
    </div>
</body>
</html>
