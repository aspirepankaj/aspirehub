@php $errors = $errors ?? new \Illuminate\Support\ViewErrorBag(); @endphp
<div class="client-dashboard-page">

    <!-- Welcome Section -->
    <section class="welcome-section">
        <div>
            <p class="welcome-eyebrow">WELCOME BACK</p>
            <h1 class="welcome-title">
                Good <span id="greetingTime">Morning</span>, <span class="brand-teal">{{ explode(' ', $clientName)[0] }}.</span>
            </h1>
            <p class="welcome-desc">Here's a quick look at your digital performance and connected platforms.</p>
        </div>

        <div class="growth-note">Your<br/>growth<br/>matters.<br/>—</div>

        <div class="welcome-buttons">
            <a href="{{ route('client.dashboard') }}" class="btn-back">
                <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
                <span>Back to Dashboard</span>
            </a>
            <a href="{{ route('client.support') }}" class="btn-support-ticket">
                <i data-lucide="message-square-more" style="width:16px;height:16px;"></i>
                <span>Open Support Ticket</span>
            </a>
        </div>
    </section>

    <!-- Stats Grid (4 Cards) -->
    <section class="stats-grid-4">
        <!-- Connected Websites -->
        <a href="{{ route('client.websites') }}" class="stat-box">
            <div class="stat-circle-icon blue">
                <i data-lucide="globe"></i>
            </div>
            <div class="stat-body">
                <div class="stat-title">Connected Websites</div>
                <div class="stat-number">{{ $stats['websites'] }}</div>
                <div class="stat-foot">
                    <span>All operational</span>
                    <i data-lucide="chevron-right" class="stat-foot-arrow" style="width:14px;height:14px;"></i>
                </div>
            </div>
        </a>

        <!-- Linked Analytics -->
        <a href="{{ route('client.marketing') }}" class="stat-box">
            <div class="stat-circle-icon mint">
                <i data-lucide="chart-no-axes-column-increasing"></i>
            </div>
            <div class="stat-body">
                <div class="stat-title">Linked Analytics</div>
                <div class="stat-number">{{ $stats['linked_analytics'] }}</div>
                <div class="stat-foot">
                    <span>Platforms connected</span>
                    <i data-lucide="chevron-right" class="stat-foot-arrow" style="width:14px;height:14px;"></i>
                </div>
            </div>
        </a>

        <!-- Active Services -->
        <div class="stat-box" style="cursor: default;">
            <div class="stat-circle-icon violet">
                <i data-lucide="layers"></i>
            </div>
            <div class="stat-body">
                <div class="stat-title">Active Services</div>
                <div class="stat-number">{{ $stats['active_services'] }}</div>
                <div class="stat-foot">
                    <span>Subscribed services</span>
                    <i data-lucide="chevron-right" class="stat-foot-arrow" style="width:14px;height:14px;"></i>
                </div>
            </div>
        </div>

        <!-- Latest Report -->
        <a href="{{ route('client.marketing') }}" class="stat-box">
            <div class="stat-circle-icon orange">
                <i data-lucide="file-text"></i>
            </div>
            <div class="stat-body">
                <div class="stat-title">Latest Report</div>
                <div class="stat-number report-month">{{ $stats['last_report_month'] }}</div>
                <div class="stat-foot">
                    <span>Generated reports</span>
                    <i data-lucide="chevron-right" class="stat-foot-arrow" style="width:14px;height:14px;"></i>
                </div>
            </div>
        </a>
    </section>

    <!-- Analytics & Reporting -->
    <section style="margin-bottom: 24px;">
        <div class="section-top">
            <div>
                <h2 class="section-title">Analytics &amp; Reporting</h2>
                <p class="section-sub">Access your connected platforms to view performance, insights, and more.</p>
            </div>
            <a href="{{ route('client.marketing') }}" class="section-link">
                <span>View All Platforms</span>
                <i data-lucide="arrow-right" style="width:14px;height:14px;"></i>
            </a>
        </div>

        <div class="platforms-row">
            @foreach($connectedPlatforms as $platform)
                <a href="{{ route('client.marketing') }}?tab={{ $platform['tab'] }}" class="platform-card-box">
                    <div class="platform-logo-wrap">
                        @if($platform['key'] === 'ga4')
                            <svg class="google-logo" viewBox="0 0 54 44" style="width:48px;height:40px;">
                                <rect x="27" y="2" width="10" height="40" rx="5" fill="#F9AB00"/>
                                <rect x="14" y="17" width="10" height="25" rx="5" fill="#E37400"/>
                                <circle cx="6" cy="37" r="5" fill="#E37400"/>
                            </svg>
                        @elseif($platform['key'] === 'gsc')
                            <svg class="console-logo" viewBox="0 0 55 44" style="width:48px;height:40px;">
                                <path d="M19 11V7q0-4 5-4h8q5 0 5 4v4" fill="none" stroke="#64748b" stroke-width="3"/>
                                <rect x="4" y="12" width="47" height="30" rx="4" fill="#cbd5e1"/>
                                <path d="M9 11h39v10H9Z" fill="#3b82f6" opacity=".85"/>
                                <path d="M9 24h9v17H9m34-17h5v17h-5" fill="#ffffff"/>
                                <circle cx="14" cy="16" r="1.5" fill="#ffffff"/>
                                <circle cx="20" cy="16" r="1.5" fill="#ffffff"/>
                                <path d="m29 24-3 5 2 6 5 2v5h5v-8c4-3 4-7 1-10v6l-5 2-4-3v-5Z" fill="#475569"/>
                            </svg>
                        @elseif($platform['key'] === 'youtube')
                            <svg class="youtube-logo" viewBox="0 0 56 44" style="width:48px;height:40px;">
                                <path d="M4 7Q28 2 51 7Q56 21 51 37Q28 42 4 37Q-1 23 4 7" fill="#FF0000"/>
                                <path d="m23 13 16 9-16 9Z" fill="#ffffff"/>
                            </svg>
                        @elseif($platform['key'] === 'gads')
                            <svg class="gads-logo" viewBox="0 0 48 48" style="width:44px;height:40px;">
                                <path fill="#4285F4" d="M12.6 35.8c-4.4-7.6-1.8-17.3 5.8-21.7 7.6-4.4 17.3-1.8 21.7 5.8l-7.7 4.5c-2-3.4-6.3-4.5-9.7-2.6-3.4 2-4.5 6.3-2.6 9.7l-7.5 4.3z"/>
                                <path fill="#FBBC04" d="M35.4 12.2l7.7-4.5c4.4 7.6 1.8 17.3-5.8 21.7-7.6 4.4-17.3 1.8-21.7-5.8l7.7-4.5c2 3.4 6.3 4.5 9.7 2.6 3.4-2 4.5-6.3 2.4-9.5z"/>
                                <circle fill="#34A853" cx="12" cy="36" r="6"/>
                            </svg>
                        @elseif($platform['key'] === 'linkedin')
                            <svg class="linkedin-logo" viewBox="0 0 44 44" style="width:40px;height:40px;">
                                <rect width="44" height="44" rx="9" fill="#0A66C2"/>
                                <path fill="#FFFFFF" d="M11 16h6v18h-6V16zm3-10a3.5 3.5 0 110 7 3.5 3.5 0 010-7zm9 10h5.7v2.5h.1c.8-1.5 2.7-3.1 5.6-3.1 6 0 7.1 3.9 7.1 9.1V34h-6v-8.4c0-2-.04-4.6-2.8-4.6-2.8 0-3.2 2.2-3.2 4.5V34h-6V16z"/>
                            </svg>
                        @elseif($platform['key'] === 'facebook')
                            <svg class="meta-logo" viewBox="0 0 58 44" fill="none" style="width:48px;height:40px;">
                                <path d="M5 31C1 24 8 8 15 8c9 0 17 29 25 29 16 0 11-29 1-29C31 8 22 37 13 37c-4 0-6-2-8-6Z" stroke="#0081FB" stroke-width="5" stroke-linecap="round"/>
                            </svg>
                        @elseif($platform['key'] === 'keyword')
                            <svg class="keywords-logo" viewBox="0 0 53 44" style="width:48px;height:40px;">
                                <rect x="2" y="8" width="10" height="30" rx="5" fill="#8b5cf6"/>
                                <path d="m29 2-16 20 16 20h13L26 22 42 2Z" fill="#3b82f6"/>
                            </svg>
                        @else
                            <i data-lucide="globe" style="width:36px;height:36px;color:#0284c7;"></i>
                        @endif
                    </div>

                    <h3 class="platform-card-title">{{ $platform['name'] }}</h3>
                    <p class="platform-card-desc">{{ $platform['description'] }}</p>

                    <div class="platform-arrow-circle">
                        <i data-lucide="arrow-right" style="width:13px;height:13px;"></i>
                    </div>

                    <svg class="card-wave-svg" viewBox="0 0 210 36" preserveAspectRatio="none">
                        <path d="M0 8C61 6 92 52 210 1V36H0Z" fill="{{ $platform['wave_color'] ?? '#e0f2fe' }}" opacity="0.6"/>
                        <path d="M0 35C89 43 138 1 210 3V36H0Z" fill="{{ $platform['wave_color'] ?? '#e0f2fe' }}" opacity="0.8"/>
                    </svg>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Encouragement Banner -->
    <section class="encouragement-banner">
        <div class="encouragement-text">
            <div class="encouragement-eyebrow">LET’S GROW TOGETHER</div>
            <h2 class="encouragement-heading">Insights today. A stronger tomorrow.</h2>
            <p class="encouragement-sub">Your online presence is working for you. Keep going!</p>
        </div>

        <svg class="mountains-svg" viewBox="0 0 370 110">
            <path class="mountain-back" d="M0 110C65 102 89 1 209 11s121 65 161 99Z"/>
            <path class="mountain-front" d="m56 110 53-29q6-7 12 0l37 29Zm55 0 66-65q8-10 16 0l61 65Z"/>
            <path class="mountain-mid" d="m176 110 88-67q7-5 13 2l63 65Z"/>
            <path class="mountain-back" d="m164 110 30-31q5-7 12-3l20 7 22 27Zm51 0 30-28 23 10 11 18Z"/>
            <path class="mountain-line" d="m200 82 38-25 35-28V12" stroke-width="1.3" fill="none" stroke-dasharray="5 5"/>
            <path class="mountain-flag" d="M273 13v-9l21 6-21 6Z"/>
            <path class="mountain-line" d="M273 4v27" stroke-width="1.5"/>
        </svg>

        <div class="quote-box">
            <blockquote class="quote-text">“Consistent progress<br/>leads to extraordinary results.”</blockquote>
            <small class="quote-author">The Aspire Hub Team</small>
        </div>
    </section>

    <script>
        (function() {
            const hour = new Date().getHours();
            let greeting = 'Morning';
            if (hour >= 12 && hour < 17) {
                greeting = 'Afternoon';
            } else if (hour >= 17) {
                greeting = 'Evening';
            }
            const el = document.getElementById('greetingTime');
            if (el) el.textContent = greeting;
        })();
    </script>
</div>