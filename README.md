<div align="center">

# ⚡ Aspire Hub
### Next-Gen Modular Multi-Tenant Enterprise Client Portal & Agency SaaS Platform

[![Laravel](https://img.shields.io/badge/Laravel-12%20%7C%2013-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3%20%7C%208.4-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Livewire](https://img.shields.io/badge/Livewire-3.x%20Volt-4E56A6?style=for-the-badge&logo=livewire&logoColor=white)](https://livewire.laravel.com/)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.4+-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Vite](https://img.shields.io/badge/Vite-Build_Tool-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev/)

<p align="center">
  <b>Enterprise-grade CRM • Marketing Analytics Hub • Automated Client Reporting • Task Synchronization</b>
</p>

[Quick Start](#-quick-start--installation) •
[Portal URLs](#-portal-access-endpoints) •
[Architecture](#-domain-driven-modular-architecture) •
[Integrations](#-third-party-integrations-hub) •
[CLI Commands](#-cli--background-sync-commands)

---

</div>

## 📌 Overview

**Aspire Hub** is a mission-critical digital agency operations portal designed for seamless client management, automated white-label maintenance reports, comprehensive multi-channel marketing analytics, and bi-directional project synchronization.

### 🌟 Core Capabilities
- 🏢 **Multi-Tenant Client Portal**: Dedicated client experience with website health metrics, billing, documents, and real-time project ticket tracker.
- 📊 **Unified Marketing Intelligence**: Integrated dashboards for Google Analytics 4, Search Console, YouTube Analytics, Google Business Profile, Google Ads, and Keyword.com.
- ⚡ **Full ClickUp Integration**: Deep two-way workspace mapping syncing spaces, folders, lists, and tasks with granular assignee status and progress.
- 📄 **Automated PDF Reports**: High-fidelity maintenance & SEO audit reports generated and dispatched directly to clients via queue-backed email pipelines.
- 🔐 **Enterprise Security**: Role-based access control (RBAC), multi-guard authentication, encrypted credential storage, and granular permission enforcement.

---

## 🛠️ Technology Stack

| Layer | Technologies |
| :--- | :--- |
| **Backend Framework** | Laravel 12 / 13 (Modern Service Architecture) |
| **Language** | PHP `^8.3` / `^8.4` (Strict Typing, Attributes) |
| **Frontend Reactive Engine** | Livewire 3 (Volt), Alpine.js |
| **Styling & Design System** | Tailwind CSS with Dark/Light Adaptive Mode |
| **Build & Bundler** | Vite 8+ |
| **Databases Supported** | MySQL 8.0+ (Local / Prod) • MSSQL (Enterprise Staging) |
| **Caching & Queues** | Database Cache Driver / Redis High-Performance Broker |
| **Mailing System** | Native Symfony Mailer with SMTP & Gmail TLS/SSL |

---

## 📋 System Prerequisites

Ensure your development workstation meets the minimum environment specifications:

- **PHP**: `>= 8.3.0` (with `pdo_mysql`, `curl`, `openssl`, `mbstring`, `fileinfo`)
- **Composer**: `^2.8`
- **Node.js**: `>= 20.x` & **npm**: `>= 10.x`
- **Database Engine**: MySQL 8.x or MariaDB 10.4+ (Default port: `3306`)

---

## 🚀 Quick Start & Installation

### 1. Clone & Install Dependencies
```bash
# Clone the repository
git clone https://github.com/aspirepankaj/aspirehub.git
cd aspirehub

# Install Composer PHP dependencies
composer install

# Install Frontend Node modules
npm install
```

### 2. Environment Configuration
```bash
# Duplicate example environment file
cp .env.example .env

# Generate secure application encryption key
php artisan key:generate
```

### 3. Database Setup
Create a fresh database named `aspire_hub_lv` in your MySQL server, then verify your `.env` connection:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aspire_hub_lv
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations & Seed Default Data
Create the schema and populate foundational roles, permissions, and default system administrator:
```bash
# Run database migrations
php artisan migrate

# Seed administrative credentials & roles
php artisan db:seed
```

> [!TIP]
> **Default Admin Account:**
> - **Email**: `admin@aspirehub.com`
> - **Password**: `password`

### 5. Compile Frontend Assets
```bash
# Development mode (Hot-Module-Replacement / HMR)
npm run dev

# Or build optimized production bundles
npm run build
```

### 6. Launch Application Server
```bash
# Standard command
php artisan serve

# Windows environment with specific PHP 8.3+ binary path:
$env:Path = "C:\php83;" + $env:Path
php artisan serve
```

---

## 🌐 Portal Access Endpoints

Once the dev server is active, access the respective portals:

| Portal | URL | Credentials / Note |
| :--- | :--- | :--- |
| 🛡️ **Admin Portal** | [`http://127.0.0.1:8000/adminadspnl`](http://127.0.0.1:8000/adminadspnl) | `admin@aspirehub.com` / `password` |
| 👤 **Client Dashboard** | [`http://127.0.0.1:8000/login`](http://127.0.0.1:8000/login) | Client account credentials |
| 👥 **Staff Portal** | [`http://127.0.0.1:8000/staffadspnl`](http://127.0.0.1:8000/staffadspnl) | Assigned agency staff members |

---

## 🏛️ Domain-Driven Modular Architecture

The repository enforces a clean **Modular DDD Structure** for scalability and maintainability:

```plaintext
app/Modules/
├── Core/                      # Platform Foundation
│   ├── Authentication/        # Multi-guard auth, roles, permissions, policies
│   ├── Dashboard/             # System overview analytics & metric widgets
│   ├── Settings/              # Application, branding & system parameters
│   ├── Notifications/         # Real-time alerts, broadcast channels
│   └── ActivityLogs/          # Audit logging & administrative security events
│
├── CRM/                       # Agency Client Management
│   ├── Clients/               # Client profiles, plans, contacts, folder mapping
│   ├── Websites/              # Managed domains, CMS links, integration tokens
│   ├── ClickUp/               # Spaces, Folders, Lists & Task synchronization
│   ├── Staff/                 # Agency team members, designations & assignments
│   └── Documents/             # Contracts, proposals, and secure file vaults
│
├── Marketing/                 # Performance & Analytics Hub
│   ├── GoogleAnalytics/       # GA4 properties, sessions, bounce rate, channels
│   ├── SearchConsole/         # Keywords, rankings, CTR, top landing pages
│   ├── GoogleAds/             # Campaign performance, ad spend, conversion rates
│   ├── GoogleBusinessProfile/ # Reviews, search queries, customer actions
│   ├── YouTube/               # Video views, subscriber velocity, watch hours
│   └── Keyword/               # Daily ranking tracker via Keyword.com API
│
├── Maintenance/               # Website Health & Automated Audits
│   ├── Reports/               # Performance scores, security audits, backups
│   └── Schedulers/            # Automated report generation & client dispatch
│
└── Shared/                    # Cross-cutting Concerns
    ├── Traits/                # Auditable, EncryptedAttributes, HasSlugs
    ├── Components/            # Reusable Livewire & Blade UI building blocks
    └── Services/              # Third-party API wrappers & HTTP utilities
```

---

## 🔌 Third-Party Integrations Hub

<details>
<summary><b>1. 🎯 ClickUp Workspace Integration</b> (Click to expand)</summary>

Connects agency spaces, folders, and client-assigned tasks into the CRM.

1. Add your ClickUp credentials in `.env`:
   ```env
   CLICKUP_API_TOKEN=pk_your_clickup_api_token
   CLICKUP_TEAM_ID=your_clickup_workspace_team_id
   ```
2. Navigate to **Admin Panel > Clients > Client Profile > ClickUp Tickets**.
3. Use **Map With ClickUp** to assign specific space folders to each client.
</details>

<details>
<summary><b>2. 📈 Google Analytics 4 (GA4)</b> (Click to expand)</summary>

1. Visit [Google Cloud Console](https://console.cloud.google.com/) and create a project.
2. Enable:
   - **Google Analytics Admin API**
   - **Google Analytics Data API**
3. Configure OAuth Consent Screen with scope:
   - `https://www.googleapis.com/auth/analytics.readonly`
4. Create an **OAuth Client ID (Web Application)**:
   - Redirect URI: `http://127.0.0.1:8000/admin/integrations/google/callback`
5. Download credentials as JSON and upload in **Client Website Integrations > Google Analytics 4**.
</details>

<details>
<summary><b>3. 🔍 Google Search Console (GSC)</b> (Click to expand)</summary>

1. In [Google Cloud Console](https://console.cloud.google.com/), enable:
   - **Google Search Console API**
2. Add OAuth Scope:
   - `https://www.googleapis.com/auth/webmasters.readonly`
3. Set Redirect URI: `http://127.0.0.1:8000/admin/integrations/google/callback`
4. Download the client JSON credentials and attach to the target client website.
</details>

<details>
<summary><b>4. 📹 YouTube Analytics</b> (Click to expand)</summary>

1. In Google Cloud Console, enable:
   - **YouTube Data API v3**
   - **YouTube Analytics API**
2. Scopes required:
   - `https://www.googleapis.com/auth/youtube.readonly`
   - `https://www.googleapis.com/auth/yt-analytics.readonly`
3. Upload credentials JSON under YouTube Integration and specify Channel ID.
4. Sync metrics: `php artisan sync:youtube-metrics`
</details>

<details>
<summary><b>5. 📍 Google Business Profile (GBP)</b> (Click to expand)</summary>

1. In Google Cloud Console, enable **Google My Business API**.
   *(Note: Requires whitelisted business verification access).*
2. Scope required: `https://www.googleapis.com/auth/business.manage`
3. Provide JSON credentials along with your GBP Location ID.
4. Sync metrics: `php artisan sync:gbp-metrics`
</details>

<details>
<summary><b>6. 📢 Google Ads API</b> (Click to expand)</summary>

1. Enable **Google Ads API** in Google Cloud Console.
2. Scope required: `https://www.googleapis.com/auth/adwords`
3. Obtain your **Developer Token** from Google Ads Manager Account (MCC) -> **Tools & Settings > API Center**.
4. Configure Customer ID and Developer Token in the portal.
5. Sync metrics: `php artisan sync:google-ads-metrics`
</details>

<details>
<summary><b>7. 🏷️ Google Tag Manager (GTM)</b> (Click to expand)</summary>

1. Enable **Tag Manager API** in Google Cloud Console.
2. Scope: `https://www.googleapis.com/auth/tagmanager.readonly`
3. Upload JSON credentials with the Container ID. Metrics load dynamically in real-time.
</details>

<details>
<summary><b>8. 🎯 Keyword.com Rank Tracker</b> (Click to expand)</summary>

1. Log into your **Keyword.com** dashboard.
2. Go to **Settings > API** and generate an API Token.
3. In the CRM, enter your **API Token** and **Project ID** to start streaming live rankings.
</details>

---

## 📧 Email & SMTP Configuration

Aspire Hub utilizes Laravel's native mail infrastructure (Symfony Mailer). Configure your SMTP provider in `.env` to enable automated PDF dispatch:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD="your-16-character-app-password"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## ⚡ CLI & Background Sync Commands

| Command | Purpose |
| :--- | :--- |
| `php artisan migrate:fresh --seed` | Full database reset and seed clean state |
| `php artisan sync:youtube-metrics` | Polls and stores latest YouTube channel metrics |
| `php artisan sync:gbp-metrics` | Syncs Google Business Profile interactions |
| `php artisan sync:google-ads-metrics` | Syncs Google Ads spend, CPC, and conversion data |
| `php artisan test` | Runs the automated test suite with in-memory SQLite |
| `php artisan optimize:clear` | Flushes all application, configuration, route & view caches |

---

## 🛡️ Quality Assurance & Testing

Run unit and feature tests against the automated in-memory SQLite database:

```bash
php artisan test
```

Verifies multi-guard auth policies, customer authorization boundaries, and livewire component lifecycle validations.

---

<div align="center">

Made with ❤️ by the **Aspire Hub Team** • All Rights Reserved

</div>
