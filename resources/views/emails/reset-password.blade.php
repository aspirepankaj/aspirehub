<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password — Aspire Hub</title>
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
            max-width: 580px;
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
        /* Gradient banner at top of card */
        .card-header {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #ec4899 100%);
            padding: 40px 40px 32px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .card-header::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 120px;
            height: 120px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }
        .card-header::after {
            content: '';
            position: absolute;
            bottom: -40px;
            left: -20px;
            width: 90px;
            height: 90px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
        }
        .lock-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 16px;
            margin-bottom: 16px;
        }
        .lock-icon svg {
            width: 32px;
            height: 32px;
            color: #fff;
        }
        .card-header h1 {
            color: #ffffff;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }
        .card-header p {
            color: rgba(255,255,255,0.8);
            font-size: 13.5px;
            font-weight: 500;
        }
        /* Card body */
        .card-body {
            padding: 36px 40px;
        }
        .greeting {
            font-size: 15.5px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 12px;
        }
        .body-text {
            font-size: 14.5px;
            line-height: 1.65;
            color: #475569;
            margin-bottom: 28px;
        }
        /* CTA Button */
        .btn-wrapper {
            text-align: center;
            margin-bottom: 28px;
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
        /* Expiry notice */
        .notice-box {
            background: #fef9ec;
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 28px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .notice-box .notice-icon {
            flex-shrink: 0;
            margin-top: 1px;
        }
        .notice-box p {
            font-size: 13px;
            color: #92400e;
            line-height: 1.5;
        }
        .notice-box strong {
            color: #78350f;
        }
        /* Fallback URL */
        .url-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 8px;
        }
        .url-box p {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 6px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .url-box code {
            font-size: 11px;
            color: #6366f1;
            word-break: break-all;
            font-family: 'Courier New', Courier, monospace;
        }
        /* Divider */
        .divider {
            height: 1px;
            background: #f1f5f9;
            margin: 24px 0;
        }
        /* Security note */
        .security-note {
            font-size: 12.5px;
            color: #94a3b8;
            text-align: center;
            line-height: 1.5;
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
        .card-footer a {
            color: #6366f1;
            text-decoration: none;
        }
        /* Bottom spacer */
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
                <div class="lock-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h1>Reset Your Password</h1>
                <p>We received a request to reset your account password.</p>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <p class="greeting">Hello {{ $notifiable->name ?? 'there' }},</p>
                <p class="body-text">
                    We received a request to reset the password associated with your Aspire Hub account. Click the button below to choose a new password. This link is valid for {{ $count }} minutes.
                </p>

                <div class="btn-wrapper">
                    <a href="{{ $url }}" class="btn">Reset My Password &rarr;</a>
                </div>

                <!-- Expiry Warning -->
                <div class="notice-box">
                    <div class="notice-icon">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z" />
                        </svg>
                    </div>
                    <p>This link will <strong>expire in {{ $count }} minutes</strong>. If you did not request a password reset, no action is required — your password will remain unchanged.</p>
                </div>

                <!-- Fallback URL -->
                <div class="url-box">
                    <p>Or copy this link into your browser:</p>
                    <code>{{ $url }}</code>
                </div>

                <div class="divider"></div>
                <p class="security-note">If you're having trouble clicking the button, copy and paste the URL above into your browser. For security, this request was made from the Aspire Hub platform.</p>
            </div>

            <!-- Footer -->
            <div class="card-footer">
                <p><strong>Aspire Hub</strong> — Enterprise SaaS Platform</p>
                <p>&copy; {{ date('Y') }} Aspire Hub. All rights reserved.</p>
            </div>
        </div>

        <p class="bottom-note">You are receiving this email because a password reset was requested for your account.</p>
    </div>
</body>
</html>
