<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Critical Maintenance Report Alert</title>
    <style type="text/css">
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; }
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f1f5f9; color: #1e293b; }
        
        .main-table { width: 100%; background-color: #f1f5f9; padding: 40px 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: transparent; }
        
        .card { background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden; }
        
        /* The requested header using the teal color #135266 */
        .card-header { background-color: #135266; padding: 35px 30px; text-align: center; }
        .logo { width: 220px; max-width: 100%; height: auto; display: block; margin: 0 auto; }
        
        .card-body { padding: 40px 30px; }
        
        .title-row { text-align: center; margin-bottom: 25px; }
        .badge { background-color: #ef4444; color: #ffffff; padding: 6px 16px; border-radius: 999px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; margin-bottom: 15px;}
        .title { font-size: 22px; font-weight: 700; color: #0f172a; margin: 0; }
        
        .info-grid { width: 100%; margin-bottom: 30px; border-top: 1px solid #e2e8f0; }
        .info-row { border-bottom: 1px solid #e2e8f0; }
        .info-label { width: 35%; padding: 15px 10px 15px 0; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; vertical-align: top; }
        .info-value { padding: 15px 0; font-size: 15px; font-weight: 600; color: #0f172a; vertical-align: top; }
        .info-value a { color: #135266; text-decoration: none; }
        
        .reason-card { background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 25px; margin-bottom: 35px; border-radius: 0 8px 8px 0; }
        .reason-title { font-size: 12px; font-weight: 800; color: #b91c1c; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 10px 0; }
        .reason-text { font-size: 15px; color: #7f1d1d; line-height: 1.6; margin: 0; }
        
        .action-container { text-align: center; margin-bottom: 10px; }
        .action-btn { display: inline-block; background-color: #135266; color: #ffffff !important; font-weight: 600; font-size: 15px; text-decoration: none; padding: 14px 32px; border-radius: 8px; transition: background-color 0.2s; box-shadow: 0 4px 6px -1px rgba(19, 82, 102, 0.2); }
        .action-btn:hover { background-color: #0e3b4a; }
        
        .footer { padding: 25px 0 0 0; text-align: center; }
        .footer p { color: #94a3b8; font-size: 13px; margin: 0 0 5px 0; }
    </style>
</head>
<body>
    <table class="main-table" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <!-- Wrapper for max width -->
                <table class="container" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td>
                            <!-- Main Card -->
                            <table class="card" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <!-- Header -->
                                <tr>
                                    <td class="card-header">
                                        <img src="https://hub.aspiredigitalsolutions.com/Aspire_Logo-01-White-2.png" alt="Aspire Hub" class="logo">
                                    </td>
                                </tr>
                                
                                <!-- Content -->
                                <tr>
                                    <td class="card-body">
                                        <div class="title-row">
                                            <span class="badge">Critical Alert</span>
                                            <h1 class="title">Maintenance Action Required</h1>
                                        </div>
                                        
                                        <p style="font-size: 15px; color: #475569; line-height: 1.6; margin: 0 0 30px 0; text-align: center;">
                                            A new maintenance report has been marked as <strong>Critical</strong>. Please review the details below immediately.
                                        </p>
                                        
                                        <!-- Information Grid -->
                                        <table class="info-grid" border="0" cellpadding="0" cellspacing="0">
                                            <tr class="info-row">
                                                <td class="info-label">Website</td>
                                                <td class="info-value">
                                                    <a href="{{ $report->website->url ?? '#' }}">{{ $report->website->url ?? 'N/A' }}</a>
                                                    @if($report->website->site_name)
                                                        <br><span style="font-size: 13px; color: #64748b; font-weight: 500;">({{ $report->website->site_name }})</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr class="info-row">
                                                <td class="info-label">Client</td>
                                                <td class="info-value">{{ $report->client->user->name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr class="info-row">
                                                <td class="info-label">Developer</td>
                                                <td class="info-value">{{ $report->developer->name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr class="info-row">
                                                <td class="info-label">Month</td>
                                                <td class="info-value">{{ $report->maintenance_month }}</td>
                                            </tr>
                                        </table>
                                        
                                        <!-- Reason Box -->
                                        <div class="reason-card">
                                            <h4 class="reason-title">Reason for Critical Status</h4>
                                            <p class="reason-text">{{ $report->critical_reason }}</p>
                                        </div>
                                        
                                        <!-- Action Button -->
                                        <div class="action-container">
                                            <a href="{{ route('admin.maintenance.edit', $report->id) }}" class="action-btn">View Full Report</a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Footer -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td class="footer">
                                        <p>&copy; {{ date('Y') }} Aspire Digital Solutions. All rights reserved.</p>
                                        <p>This is an automated system notification.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
