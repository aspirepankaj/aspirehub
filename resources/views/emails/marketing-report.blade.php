<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Marketing & SEO Performance Report' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f1f5f9;
            color: #334155;
            -webkit-font-smoothing: antialiased;
            padding: 40px 16px;
        }
        .wrapper {
            max-width: 620px;
            margin: 0 auto;
        }
        .card {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }
        .card-header {
            background: #17475a;
            padding: 36px 32px 30px;
            text-align: center;
            color: #ffffff;
        }
        .badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.15);
            color: #e2e8f0;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .card-header h1 {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.3px;
            margin-bottom: 6px;
            color: #ffffff;
        }
        .card-header p {
            font-size: 13px;
            color: #94d2bd;
            font-weight: 500;
        }
        .card-body {
            padding: 32px;
        }
        .greeting {
            font-size: 15px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 14px;
        }
        .text {
            font-size: 14px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 24px;
        }
        .personal-note {
            background-color: #f8fafc;
            border-left: 4px solid #17475a;
            padding: 14px 18px;
            border-radius: 0 10px 10px 0;
            margin-bottom: 24px;
            font-size: 13px;
            color: #334155;
            font-style: italic;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 28px;
            border-collapse: separate;
            border-spacing: 8px;
        }
        .stats-row {
            display: table-row;
        }
        .stat-card {
            display: table-cell;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            width: 33.33%;
        }
        .stat-label {
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .stat-value {
            font-size: 20px;
            font-weight: 800;
            color: #17475a;
        }
        .attachment-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
        }
        .attachment-icon {
            font-size: 24px;
            margin-right: 14px;
            line-height: 1;
        }
        .attachment-title {
            font-size: 13px;
            font-weight: 700;
            color: #166534;
        }
        .attachment-desc {
            font-size: 11px;
            color: #15803d;
            margin-top: 2px;
        }
        .card-footer {
            border-top: 1px solid #f1f5f9;
            padding: 24px 32px;
            background: #fafafa;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="card-header">
                <div class="badge">Performance Report</div>
                <h1>{{ $website->site_name }}</h1>
                <p>{{ $reportData['formattedDateRange'] }}</p>
            </div>

            <div class="card-body">
                <p class="greeting">Hi {{ $client->user->name ?? 'Valued Client' }},</p>
                <p class="text">
                    Your monthly performance and SEO analysis is ready. Below is a quick overview of key highlights for 
                    <strong>{{ $website->site_name }}</strong> during <strong>{{ $reportData['formattedDateRange'] }}</strong>.
                </p>

                @if(!empty($personalMessage))
                    <div class="personal-note">
                        "{{ $personalMessage }}"
                    </div>
                @endif

                {{-- Highlights Grid --}}
                <div class="stats-grid">
                    <div class="stats-row">
                        @if($reportData['hasGa4'])
                            <div class="stat-card">
                                <div class="stat-label">GA4 Sessions</div>
                                <div class="stat-value">{{ number_format($reportData['ga4Data']['overall_summary']['sessions'] ?? 0) }}</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-label">Total Users</div>
                                <div class="stat-value">{{ number_format($reportData['ga4Data']['overall_summary']['active_users'] ?? 0) }}</div>
                            </div>
                        @endif

                        @if($reportData['hasGsc'])
                            <div class="stat-card">
                                <div class="stat-label">Search Clicks</div>
                                <div class="stat-value">{{ number_format($reportData['gscData']['summary']['clicks'] ?? 0) }}</div>
                            </div>
                        @endif

                        @if($reportData['hasKeyword'])
                            <div class="stat-card">
                                <div class="stat-label">Top 10 Keywords</div>
                                <div class="stat-value">{{ $reportData['keywordData']['summary']['top_10'] ?? 0 }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- PDF Notice Box --}}
                <div class="attachment-box">
                    <div class="attachment-icon">&#128196;</div>
                    <div>
                        <div class="attachment-title">Full PDF Report Attached</div>
                        <div class="attachment-desc">
                            Your comprehensive multi-page SEO & Marketing Report (complete with traffic channels, keyword movements, and rankings) is attached to this email as a PDF.
                        </div>
                    </div>
                </div>

                <p class="text" style="margin-bottom: 0;">
                    If you have any questions or would like to review recommendations together, please don't hesitate to reply directly to this email.
                </p>
            </div>

            <div class="card-footer">
                &copy; {{ date('Y') }} Aspire Digital Solutions. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
