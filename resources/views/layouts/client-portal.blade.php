<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ 
          dark: localStorage.getItem('aspire-theme') === 'dark' || localStorage.getItem('client-theme') === 'dark', 
          mobileSidebar: false,
          toggleDark() {
              this.dark = !this.dark;
              localStorage.setItem('aspire-theme', this.dark ? 'dark' : 'light');
              localStorage.setItem('client-theme', this.dark ? 'dark' : 'light');
              if (this.dark) {
                  document.documentElement.classList.add('dark');
                  document.body.classList.add('dark');
              } else {
                  document.documentElement.classList.remove('dark');
                  document.body.classList.remove('dark');
              }
              this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
          },
          filterNav(q) {
              document.querySelectorAll('.portal-nav-item').forEach(b => {
                  const label = b.getAttribute('data-label') || '';
                  b.style.display = label.toLowerCase().includes(q.toLowerCase()) ? 'flex' : 'none';
              });
          }
      }" 
      x-init="
          if (dark) {
              document.documentElement.classList.add('dark');
              document.body.classList.add('dark');
          } else {
              document.documentElement.classList.remove('dark');
              document.body.classList.remove('dark');
          }
          $nextTick(() => { if (window.lucide) lucide.createIcons(); });
      " 
      :class="{ 'dark': dark }">
<head>
    <script>
        if (localStorage.getItem('aspire-theme') === 'dark' || localStorage.getItem('client-theme') === 'dark' || (!('aspire-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ?? 'Dashboard' }} | Aspire Hub Client Portal</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('aspire-triangle.svg') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Caveat:wght@600;700&display=swap" rel="stylesheet">

    <!-- Tailwind / App Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Client Portal Stylesheet (Loaded after Vite to preserve exact typography and styles) -->
    <link rel="stylesheet" href="{{ asset('css/client-portal.css') }}?v={{ time() }}" />
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    @livewireStyles
</head>
<body>

<div class="portal-layout">
    <!-- Sidebar -->
    <aside class="portal-sidebar" :class="mobileSidebar ? 'is-open' : ''" id="sidebar">
        <div class="brand">
            <svg viewBox="0 0 44 44" fill="none"><path d="M23 2 42 36H30L20 18l-3 6h7l5 8H12L6 41H0L19 8l4 7-12 21h23L22 15l-3-5Z" fill="currentColor"/><path d="M10 40h31" stroke="currentColor" stroke-width="2"/></svg>
            <div>
                <div class="brand-word">ASPIRE</div>
                <div class="brand-hub">HUB</div>
            </div>
        </div>
        <div class="brand-caption">CLIENT PORTAL</div>

        <label class="quick-search">
            <i data-lucide="search" style="width:15px;height:15px"></i>
            <input id="quickSearch" placeholder="Quick search..." @input="filterNav($event.target.value)" />
            <span>⌘K</span>
        </label>

        <nav class="portal-nav" id="navList">
            @php
                $currentRoute = request()->route()?->getName();
            @endphp
            <a href="{{ route('client.dashboard') }}" class="portal-nav-item {{ $currentRoute === 'client.dashboard' ? 'active' : '' }}" data-label="Dashboard">
                <i data-lucide="layout-grid"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('client.websites') }}" class="portal-nav-item {{ str_starts_with($currentRoute ?? '', 'client.websites') ? 'active' : '' }}" data-label="My Websites">
                <i data-lucide="globe"></i>
                <span>My Websites</span>
            </a>
            <a href="{{ route('client.marketing') }}" class="portal-nav-item {{ $currentRoute === 'client.marketing' ? 'active' : '' }}" data-label="Marketing Reports">
                <i data-lucide="megaphone"></i>
                <span>Marketing Reports</span>
            </a>
            <a href="{{ route('client.maintenance') }}" class="portal-nav-item {{ str_starts_with($currentRoute ?? '', 'client.maintenance') ? 'active' : '' }}" data-label="Maintenance Reports">
                <i data-lucide="settings"></i>
                <span>Maintenance Reports</span>
            </a>
            <a href="{{ route('client.support') }}" class="portal-nav-item {{ str_starts_with($currentRoute ?? '', 'client.support') ? 'active' : '' }}" data-label="Support Center">
                <i data-lucide="life-buoy"></i>
                <span>Support Center</span>
            </a>
            <a href="{{ route('client.documents') }}" class="portal-nav-item {{ str_starts_with($currentRoute ?? '', 'client.documents') ? 'active' : '' }}" data-label="Resources">
                <i data-lucide="file-text"></i>
                <span>Resources</span>
            </a>
            <a href="{{ route('client.profile') }}" class="portal-nav-item {{ str_starts_with($currentRoute ?? '', 'client.profile') ? 'active' : '' }}" data-label="My Profile">
                <i data-lucide="user-round"></i>
                <span>My Profile</span>
            </a>
        </nav>

        <div class="portal-sidebar-footer">
            @if(session()->has('impersonator_id'))
            <div style="margin-bottom: 10px;">
                <a href="{{ route('impersonate.stop') }}" class="portal-nav-item" style="color: #d97706; background: rgba(245, 158, 11, 0.08); height: 32px; font-size: 11.5px; padding: 0 10px;" data-label="Switch Back to Admin">
                    <i data-lucide="log-out" style="width: 14px; height: 14px;"></i>
                    <span>Switch to Admin</span>
                </a>
            </div>
            @endif
            <div class="portal-profile">
                <div class="portal-avatar">
                    @if(auth()->user()->client?->profile_image)
                        <img src="{{ Str::startsWith(auth()->user()->client->profile_image, 'http') ? auth()->user()->client->profile_image : asset('storage/' . auth()->user()->client->profile_image) }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;" onerror="this.outerHTML=`{{ auth()->user()->getInitials() }}`" />
                    @else
                        {{ auth()->user()->getInitials() }}
                    @endif
                </div>
                <div class="portal-profile-info">
                    <div class="portal-profile-name">{{ auth()->user()->name }}</div>
                    <div class="portal-profile-sub">{{ $clientCompanyName ?? 'Aspire Hub' }}</div>
                </div>
            </div>
            <div class="portal-actions">
                <button type="button" class="portal-btn-theme" @click="toggleDark()">
                    <i :data-lucide="dark ? 'sun' : 'moon'" style="width: 14px; height: 14px;"></i>
                    <span x-text="dark ? 'Light' : 'Dark'">Dark</span>
                </button>
                <form method="POST" action="{{ route('logout') }}" style="flex: 1; display: flex; margin: 0;">
                    @csrf
                    <button type="submit" class="portal-btn-logout" style="width: 100%;">
                        <i data-lucide="log-out" style="width: 14px; height: 14px;"></i>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="portal-main {{ request()->routeIs('client.marketing*') ? 'is-marketing-report' : '' }}">
        @if(request()->routeIs('client.marketing*', 'client.dashboard*') || request()->is('client/dashboard*'))
            <!-- Giant Background Watermark Aspire Triangle Logo (Client Marketing Reports & Dashboard) -->
            <div class="background-mark" aria-hidden="true">
                <svg viewBox="0 0 450 461" fill="currentColor">
                    <path d="M260.5 41.2c-12.1 19.8-22 36.6-22 37.2s4.5 8.5 9.9 17.6c9.1 15.2 14.6 24.1 36.9 60.5 4.4 7.1 10.6 17.3 13.7 22.5s9.2 15.1 13.5 22c6.7 10.8 23.6 38.4 36.8 60 2.2 3.6 8.6 13.9 14.2 23 24.1 38.9 83.5 136.7 83.5 137.6 0 .2-8.6.3-19.1.2l-19.1-.3-8.9-14.5c-4.8-8-10.4-17.1-12.4-20.2l-3.6-5.8H175.3l-2.5 3.7c-1.4 2.1-7.8 12.3-14.3 22.8-12 19.5-18.6 30.1-26 41.6l-4.3 6.5 3.2.2c11.1.5 393.2-.1 406.3-.6l16.2-.7-12.1-20c-6.6-11-18.5-30.6-26.3-43.5s-18.2-30-23-38c-4.9-8-13.2-21.7-18.5-30.5s-11.1-18.3-12.7-21c-1.7-2.8-10.4-17.2-19.3-32-9-14.9-19.3-32-23-38-3.7-6.1-12.3-20.5-19.2-32-6.9-11.6-13.9-23.3-15.5-26-6.4-10.5-21.3-35.7-31-52-13.2-22.5-12.1-20.6-26.2-44.2-6.8-11.5-16.9-28.2-22.3-37.3-15.7-26.3-21.1-35-21.7-35-.3.1-10.5 16.3-22.6 36.2"/>
                    <path d="M203.9 132.8c-15.1 24-17 27.1-48.3 77.7-10.9 17.6-25.5 41-32.4 52s-15.8 25.2-19.7 31.5c-4 6.3-17.9 28.6-31 49.5s-26.4 42.1-29.4 47c-3.1 5-10.8 17.3-17.1 27.4-6.3 10-14 22.5-17.1 27.6l-5.7 9.3 15.2.6c8.3.4 22.6.5 31.8.3 9.2-.1 16.9.1 17 .5.2.5 3.8.5 8 .2l7.6-.7 19.8-31.1c10.8-17.1 21.6-34 24-37.6 4-6.2 17.7-27.7 27.1-42.5 2.4-3.9 11.4-17.8 19.8-31 8.5-13.2 19.8-31 25.2-39.5 12.1-19.3 23.4-36.3 23.8-35.8.3.3 8 12.8 32.6 53.1 6 9.7 10.9 18 10.9 18.2 0 .3-11 .5-24.4.5h-24.4l-8 12.7c-12.8 20.6-18.2 29.7-18.2 30.5 0 1.1 172.2 1 173.9-.1 1.6-1 .7-2.6-17.9-33.1-6.2-10.2-15.8-26.2-21.5-35.5-5.6-9.4-15.7-26-22.5-37s-16.5-26.8-21.5-35c-5.1-8.3-12.1-19.7-15.7-25.5-3.5-5.8-13.2-21.5-21.5-35s-17.5-28.4-20.6-33.2l-5.5-8.8z"/>
                </svg>
            </div>
        @endif
{{--
        @if(!request()->routeIs('client.marketing*', 'client.dashboard*') && !request()->is('client/dashboard*'))
            <!-- Top Wave Vector SVG (Only for Non-Marketing Client Pages) -->
            <svg class="top-waves-container" viewBox="0 0 1000 250" preserveAspectRatio="none">
                <path d="M100 250C400 190 390 80 550 0H880C650 145 440 250 100 250Z" fill="#dcf1f7" opacity="0.6"/>
                <path d="M250 250C600 210 570 45 680 0H1000C780 135 590 245 250 250Z" fill="#edf7fa" opacity="0.85"/>
            </svg>
        @endif
--}}

        <div class="portal-inner">
            <!-- Topbar -->
            <header class="portal-topbar">
                <div class="portal-breadcrumbs">
                    <button type="button" class="mobile-menu-btn" @click="mobileSidebar = !mobileSidebar">
                        <i data-lucide="menu"></i>
                    </button>
                    <i data-lucide="house" style="width:15px;height:15px;opacity:0.7;"></i>
                    <span>Client</span>
                    <i data-lucide="chevron-right" style="width:13px;height:13px;opacity:0.5;"></i>
                    <strong>{{ $title ?? 'Dashboard' }}</strong>
                </div>
                <div class="portal-topbar-tools">
                    <a href="{{ route('client.notifications') }}" class="topbar-tool-btn notification" title="Notifications">
                        <i data-lucide="bell" style="width:16px;height:16px;"></i>
                    </a>
                    <a href="{{ route('client.profile') }}" class="topbar-tool-btn" title="Settings">
                        <i data-lucide="settings" style="width:16px;height:16px;"></i>
                    </a>
                </div>
            </header>

            <!-- Page Slot -->
            {{ $slot }}

            <!-- Footer -->
            <footer class="portal-footer">
                <div class="portal-footer-left">
                    <span>© 2026 <strong>Aspire Digital Solutions.</strong> All rights reserved.</span>
                    <span class="portal-footer-divider">Next-Gen Enterprise Management System.</span>
                </div>
                <div class="portal-footer-right">
                    <span>Developed with</span>
                    <i data-lucide="heart" class="heart-icon" style="width:14px;height:14px;"></i>
                    <span>by</span>
                    <span class="footer-brand-title">
                        <img src="{{ asset('aspire-favicon.png') }}" alt="Aspire Digital Solutions" style="width:19px;height:19px;object-fit:contain;flex-shrink:0;" />
                        Aspire Digital Solutions
                    </span>
                </div>
            </footer>
        </div>
    </main>
</div>

@livewireScripts

<script>
    function renderLucideIcons() {
        if (window.lucide) {
            window.lucide.createIcons();
        }
    }
    document.addEventListener('DOMContentLoaded', renderLucideIcons);
    document.addEventListener('livewire:navigated', renderLucideIcons);
    document.addEventListener('livewire:load', renderLucideIcons);
    document.addEventListener('keydown', e => {
        if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            document.getElementById('quickSearch')?.focus();
        }
    });
</script>
</body>
</html>
