<div align="center">

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

| Layer                              | Technologies                                            |
| :--------------------------------- | :------------------------------------------------------ |
| **Backend Framework**        | Laravel 12 / 13 (Modern Service Architecture)           |
| **Language**                 | PHP`^8.3` / `^8.4` (Strict Typing, Attributes)      |
| **Frontend Reactive Engine** | Livewire 3 (Volt), Alpine.js                            |
| **Styling & Design System**  | Tailwind CSS with Dark/Light Adaptive Mode              |
| **Build & Bundler**          | Vite 8+                                                 |
| **Databases Supported**      | MySQL 8.0+ (Local / Prod) • MSSQL (Enterprise Staging) |
| **Caching & Queues**         | Database Cache Driver / Redis High-Performance Broker   |
| **Mailing System**           | Native Symfony Mailer with SMTP & Gmail TLS/SSL         |

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
>
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

| Portal                       | URL                                                                       | Credentials / Note                     |
| :--------------------------- | :------------------------------------------------------------------------ | :------------------------------------- |
| 🛡️**Admin Portal**   | [`http://127.0.0.1:8000/adminadspnl`](http://127.0.0.1:8000/adminadspnl) | `admin@aspirehub.com` / `password` |
| 👤**Client Dashboard** | [`http://127.0.0.1:8000/login`](http://127.0.0.1:8000/login)             | Client account credentials             |
| 👥**Staff Portal**     | [`http://127.0.0.1:8000/staffadspnl`](http://127.0.0.1:8000/staffadspnl) | Assigned agency staff members          |

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
   - **Local Redirect URI**: `http://127.0.0.1:8000/admin/integrations/google/callback`
   - **Production Redirect URI**: `https://hub.aspiredigitalsolutions.com/admin/integrations/google/callback`
5. Download credentials as JSON and upload in **Client Website Integrations > Google Analytics 4**.

</details>

<details>
<summary><b>3. 🔍 Google Search Console (GSC)</b> (Click to expand)</summary>

1. In [Google Cloud Console](https://console.cloud.google.com/), enable:
   - **Google Search Console API**
2. Add OAuth Scope:
   - `https://www.googleapis.com/auth/webmasters.readonly`
3. Set Redirect URIs:
   - **Local**: `http://127.0.0.1:8000/admin/integrations/google/callback`
   - **Production**: `https://hub.aspiredigitalsolutions.com/admin/integrations/google/callback`
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
<summary><b>6. 📢 Google Ads API Integration & Setup Guide</b> (Click to expand)</summary>

### A. Google Cloud Console Configuration

1. Go to [Google Cloud Console](https://console.cloud.google.com/) and select or create your project.
2. Enable the **Google Ads API**:
   - Navigate to **APIs & Services > Library**.
   - Search for **Google Ads API** and click **Enable**.
3. Configure the **OAuth Consent Screen**:
   - User Type: **External**.
   - Add App Name, User support email, and Developer contact information.
   - Under **Scopes**, add: `https://www.googleapis.com/auth/adwords` (or search for Google Ads API scope).
   - Add test users (your Google account email) if the app is in "Testing" mode.
4. Create **OAuth 2.0 Client ID**:
   - Go to **APIs & Services > Credentials > Create Credentials > OAuth client ID**.
   - Application type: **Web application**.
   - Name: `Aspire Hub Google Ads`.
   - **Authorized JavaScript origins**:
     - **Local**: `http://127.0.0.1:8000` and `http://localhost:8000`
     - **Production**: `https://hub.aspiredigitalsolutions.com` and `http://hub.aspiredigitalsolutions.com`
   - **Authorized redirect URIs** (Must be exact):
     - **Local Environment**:
       ```text
       http://127.0.0.1:8000/admin/integrations/google/callback
       http://localhost:8000/admin/integrations/google/callback
       ```
     - **Production Environment (`hub.aspiredigitalsolutions.com`)**:
       ```text
       https://hub.aspiredigitalsolutions.com/admin/integrations/google/callback
       http://hub.aspiredigitalsolutions.com/admin/integrations/google/callback
       ```
5. Click **Create** and download the credentials as `credentials.json`.

---

### B. Google Ads Developer Token

1. Log in to your **Google Ads Manager Account (MCC)**.
2. Navigate to **Tools and Settings (or Admin) > API Center**.
3. Copy your **Developer Token** (e.g., `ABc123dEfGhIjK456lMnOp`).
4. **Where to enter in Aspire Hub**:
   - Aspire Hub portal mein **Clients > [Select Client] > Integrations > Google Ads** par jayein.
   - Jab aap **`credentials.json`** file select karte hain, toh modal ke andar **"Google Ads Developer Token"** ka input field aayega.
   - Wahan par ye Developer Token paste karke **Save Configuration** par click karein. Token credentials ke saath securely save ho jayega.

---

### C. MCC ID (Manager Account) & Customer ID (Client Account)

Google Ads API requires both the managing agency's **MCC ID** (`login-customer-id`) and the individual client's **Customer ID** to pull campaign and metrics data.

- **MCC ID (Manager Customer Account ID)**: 10-digit ID of your agency Google Ads Manager account (e.g., `301-636-5280` -> `3016365280`).
- **Customer ID (Client Ad Account ID)**: 10-digit ID of the specific client's Google Ads account (e.g., `692-908-9276` -> `6929089276`).

#### 💡 How to format the Property ID in Aspire Hub:

In Aspire Hub, when setting the **Property ID** for a client's Google Ads integration:

- **Managed Accounts (Agency MCC + Client)**:
  Combine both 10-digit numbers (**20 digits total**):

  ```text
  [10-digit MCC ID][10-digit Client Customer ID]
  Example: 3016365280,6929089276
  ```

  > *Aspire Hub automatically detects 20-digit IDs, using the first 10 digits as `login-customer-id` (MCC) and the last 10 digits as the target account's `customer-id`.*
  >
- **Direct / Standalone Accounts**:
  Enter the single 10-digit Customer ID without hyphens:

  ```text
  Example: 6929089276
  ```

---

### D. Connecting in Aspire Hub Portal

1. In the **Admin Portal** or **Staff Portal**, navigate to **Clients > [Select Client] > Integrations**.
2. Select **Google Ads**:
   - Upload the downloaded `credentials.json` (or paste Client ID & Client Secret).
   - Enter your **Google Ads Developer Token**.
   - Click **Save Credentials**.
3. Click **Connect with Google** to complete the OAuth 2.0 authorization.
4. Under **Property / Customer ID**, enter the combined 20-digit ID (MCC + Customer ID) or 10-digit Customer ID, then click **Save Property ID**.
5. Click **Sync Data** or run the CLI sync command:
   ```bash
   php artisan sync:google-ads-metrics
   ```
6. Google Ads metrics (clicks, impressions, cost, conversions, CTR, CPC, daily performance, top campaigns, and keywords) will now stream directly to:
   - **Admin / Staff Marketing Reports**
   - **Client Dashboard** (`/client/marketing-reports?tab=gads`)
   - **Client PDF Monthly Reports**

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

<details>
<summary><b>9. 📘 Facebook Insights & Ads API</b> (Click to expand)</summary>

1. Visit [Meta for Developers](https://developers.facebook.com/) and create a Business App.
2. In **Meta Business Manager (Business Settings)**:
   - Navigate to **Users > System Users** and click **Add** (Admin system user role).
   - Under **Assigned Assets**, assign your **Facebook Page** with manage/view permissions and your **Ad Account** with analyze/read permissions.
   - Click **Generate New Token**, select your App, set token expiration to **Never / 60 days**, and select permissions:
     - `pages_show_list`
     - `pages_read_engagement`
     - `pages_read_user_content`
     - `ads_read`
     - `read_insights`
3. Get your **Page ID**:
   - Go to your Facebook Page > **About** / Settings to find your Page Numeric ID.
4. Get your **Ad Account ID**:
   - In Meta Ads Manager / Business Settings, copy your Account ID (format: `act_XXXXXXXXX` or number without `act_`).
5. In Aspire Hub Admin / Staff Portal:
   - Navigate to **Clients > [Select Client] > Integrations > Facebook**.
   - Enter **System User Access Token**, **Page ID**, and **Ad Account ID**, then click **Save Configuration**.
6. Real-time metrics will now populate in Client Marketing Reports and PDF downloads.

</details>

<details>
<summary><b>10. 💼 LinkedIn Page Analytics & OAuth Integration Guide</b> (Click to expand)</summary>

### A. LinkedIn Developer Portal Setup

1. Log in to [LinkedIn Developer Portal](https://www.linkedin.com/developers/) and click **Create App**.
2. Fill in App Details:
   - **App Name**: `Aspire Hub`
   - **LinkedIn Page**: Link your agency or business LinkedIn Company Page.
   - **App Logo**: Upload your agency logo.
3. Under the **Products** tab, request access to:
   - **Sign In with LinkedIn using OpenID Connect** (Grants `openid`, `profile`, `email` scopes).
   - **Share on LinkedIn** (or **Community Management API** / Marketing Developer Platform for page analytics).
4. Under the **Auth** tab:
   - Locate your **Application Credentials** (**Client ID** and **Client Secret**).
   - Under **OAuth 2.0 settings > Authorized redirect URLs for your app**, click the plus icon and add both exact URLs:
     - **Local Environment**:
       ```text
       http://127.0.0.1:8000/admin/integrations/linkedin/callback
       http://localhost:8000/admin/integrations/linkedin/callback
       ```
     - **Production Environment (`hub.aspiredigitalsolutions.com`)**:
       ```text
       https://hub.aspiredigitalsolutions.com/admin/integrations/linkedin/callback
       http://hub.aspiredigitalsolutions.com/admin/integrations/linkedin/callback
       ```
5. Click **Update** to save the redirect URIs.

---

### B. Entering Credentials in Aspire Hub Portal
1. Portal mein **Clients > [Select Client] > Integrations > LinkedIn** par jayein.
2. **Configure Credentials** par click karein. Modal popup open hoga:
   - **Client ID**: LinkedIn Developer App se copy kiya hua Client ID paste karein.
   - **Client Secret**: LinkedIn Developer App ka Client Secret paste karein.
   - **Scopes**: By default `openid profile email` set rehta hai.
3. **Save Configuration** par click karein.

---

### C. Connect with LinkedIn & Page Selection
1. **Connect with LinkedIn** (ya **Continue with LinkedIn**) button par click karein.
2. LinkedIn ka authorization dialog open hoga — account login karke permissions approve karein.
3. Callback successful hone ke baad:
   - Portal automatically aapke administered **LinkedIn Company Pages** ko load karega.
   - Dropdown se apna **Target Page** select karke save karein.
   - *(Optional / Manual)*: Agar dropdown ki jagah manual ID dalni ho, toh Company Page URL se **Organization ID** (e.g. `https://www.linkedin.com/company/10482910/...` -> ID `10482910`) enter karke **Save Organization ID** karein.

---

### D. Data Sync & Reports
1. **Sync Data** par click karein.
2. LinkedIn metrics directly stream hone lagenge:
   - Total & Organic Followers (`/rest/organizationFollowerStatistics`)
   - Page Views, Clicks, and Impressions (`/rest/organizationPageStatistics`)
   - Engagement rate and top updates
3. Metrics automatically available ho jayenge:
   - **Admin / Staff Marketing Reports**
   - **Client Portal Dashboard** (`/client/marketing-reports?tab=linkedin`)
   - **Monthly PDF Marketing Reports**

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

| Command                                 | Purpose                                                     |
| :-------------------------------------- | :---------------------------------------------------------- |
| `php artisan migrate:fresh --seed`    | Full database reset and seed clean state                    |
| `php artisan sync:youtube-metrics`    | Polls and stores latest YouTube channel metrics             |
| `php artisan sync:gbp-metrics`        | Syncs Google Business Profile interactions                  |
| `php artisan sync:google-ads-metrics` | Syncs Google Ads spend, CPC, and conversion data            |
| `php artisan test`                    | Runs the automated test suite with in-memory SQLite         |
| `php artisan optimize:clear`          | Flushes all application, configuration, route & view caches |

---

## 🛡️ Quality Assurance & Testing

Run unit and feature tests against the automated in-memory SQLite database:

```bash
php artisan test
```

Verifies multi-guard auth policies, customer authorization boundaries, and livewire component lifecycle validations.

---

<div align="center">
