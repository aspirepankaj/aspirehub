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
    <main class="portal-main">
        <!-- Top Wave Vector SVG -->
        <svg class="top-waves-container" viewBox="0 0 1000 250" preserveAspectRatio="none">
            <path d="M100 250C400 190 390 80 550 0H880C650 145 440 250 100 250Z" fill="#dcf1f7" opacity="0.6"/>
            <path d="M250 250C600 210 570 45 680 0H1000C780 135 590 245 250 250Z" fill="#edf7fa" opacity="0.85"/>
        </svg>

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
