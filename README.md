# Aspire Hub

**Aspire Hub** is a custom enterprise SaaS platform built with modern Laravel architecture and domain-driven modularity.

---

## Tech Stack

- **Backend**: Laravel 12/13 (PHP 8.4+)
- **Frontend**: Livewire 3 (Volt), Tailwind CSS, Alpine.js
- **Database**: MySQL (Local) / Microsoft SQL Server (Staging/Prod)

---

## Prerequisites

Ensure you have the following installed on your local environment:
- **PHP**: version `8.4` or higher
- **Composer**: version `2.8` or higher
- **Node.js & npm**: version `22.x` / `10.x` or higher
- **Database**: MySQL (running locally on port `3306`)

---

## Step-by-Step Installation & Setup

Follow these commands to get the application running on your local machine:

### 1. Install Dependencies
Run Composer to install all PHP packages and npm to install node dependencies:
```bash
composer install
npm install
```

### 2. Configure Environment Files
Copy the example environment file to create your local `.env`:
```bash
cp .env.example .env
```

### 3. Generate Application Key
Generate a secure encryption key for the application:
```bash
php artisan key:generate
```

### 4. Database Setup
1. Create a database named `aspire_hub_lv` in your local MySQL instance.
2. Verify the database credentials in your `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=aspire_hub_lv
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### 5. Run Database Migrations
Run the migrations to create the standard Laravel tables and our custom enterprise tables (prefixed with `adspv_`):
```bash
php artisan migrate
```

### 6. Seed Default Administrator Account
Seed the database to create the default administrator role, permissions, and active admin account:
```bash
php artisan db:seed
```

**Admin Portal Credentials:**
- **Email**: `admin@aspirehub.com`
- **Password**: `password`

### 7. Compile Frontend Assets
Build or compile the assets using Vite:
```bash
# Run in development mode (hot-reloads changes)
npm run dev

# Or build for production
npm run build
```

### 8. Run Local Server
Start the Laravel development server:
```bash
php artisan serve
```
Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.
- **Client Login URL**: [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)
- **Admin Portal URL**: [http://127.0.0.1:8000/adminadspnl](http://127.0.0.1:8000/adminadspnl) (Requires Admin login)
- **Staff Portal URL**: [http://127.0.0.1:8000/staffadspnl](http://127.0.0.1:8000/staffadspnl) (Prepared placeholder)

---

## Email & SMTP Configuration
The platform uses **Laravel's native mail infrastructure** (with Symfony Mailer). No external Composer packages are required.

To enable sending PDF maintenance reports directly to client emails, update the following keys in your `.env` file using your Google Gmail account & App Password:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-gmail-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Folder Architecture

The project is structured under **Domain Driven Modular Architecture**:
```
app/
└── Modules/
    ├── Core/
    │   ├── Authentication/ (Login, Logout, Guard validations)
    │   ├── Dashboard/      (Active Admin Dashboard Livewire Component)
    │   ├── Settings/       (Prepared placeholders)
    │   ├── Notifications/  (Prepared placeholders)
    │   └── ActivityLogs/   (Prepared placeholders)
    ├── CRM/
    │   ├── Clients/        (Prepared placeholders)
    │   ├── Staff/          (Prepared placeholders)
    │   ├── Websites/       (Prepared placeholders)
    │   └── Documents/      (Prepared placeholders)
    ├── Marketing/          (Prepared placeholder)
    ├── Maintenance/        (Prepared placeholder)
    ├── Support/            (Prepared placeholder)
    ├── Integrations/       (Prepared placeholder)
    └── Shared/             (Shared components, base classes, traits, enums)
```

---## Google Analytics 4 (GA4) Setup Guide

To configure Google Analytics 4 integration in the Admin or Staff panel, follow these steps to retrieve your Google OAuth Credentials:

### Step 1: Create a Project in Google Cloud Console
1. Go to the [Google Cloud Console](https://console.cloud.google.com/).
2. Create a **New Project** (e.g., *Aspire Hub Integrations*).
3. Navigate to **APIs & Services > Library**.
4. Search for and **Enable** the following APIs:
   - **Google Analytics Admin API** (required to fetch analytics properties list)
   - **Google Analytics Data API** (required to fetch and run reporting data queries)

### Step 2: Configure the OAuth Consent Screen
1. In the Google Cloud Console, navigate to the **OAuth Consent Screen** tab on the left sidebar.
2. Select **User Type: External** and click **Create**.
3. Fill in the required application details:
   - **App name**: (e.g., *Aspire Hub*)
   - **User support email**
   - **Developer contact information**
4. Under the **Scopes** section, add the following scopes for GA4 access:
   - `https://www.googleapis.com/auth/analytics.readonly` (to read Google Analytics data)
5. Save and continue.

### Step 3: Generate OAuth Credentials (Client ID & Client Secret)
1. Go to the **Credentials** tab on the left sidebar.
2. Click **Create Credentials** at the top and select **OAuth client ID**.
3. Set the **Application type** to **Web application**.
4. Under **Authorized redirect URIs**, add the callback URI for your application:
   - `http://127.0.0.1:8000/admin/integrations/google/callback`
5. Click **Create**. You will be presented with your **Client ID** and **Client Secret**.
6. Download the credentials as a `.json` file. This file will be uploaded under the integrations tab in the Admin or Staff Portal to complete the connection setup.

========================================================================

## Google Search Console (GSC) Setup Guide

To configure Google Search Console integration, follow these similar steps to retrieve your GSC OAuth Credentials:

### Step 1: Create a Project in Google Cloud Console
1. Go to the [Google Cloud Console](https://console.cloud.google.com/).
2. Create a **New Project** or select your existing integrations project.
3. Navigate to **APIs & Services > Library**.
4. Search for and **Enable** the following API:
   - **Google Search Console API** (required to fetch search queries, pages, and performance data)

### Step 2: Configure the OAuth Consent Screen
1. In the Google Cloud Console, navigate to the **OAuth Consent Screen** tab.
2. Under the **Scopes** section, make sure to add the following scope for GSC access:
   - `https://www.googleapis.com/auth/webmasters.readonly` (to read Google Search Console data)
3. Save and continue.

### Step 3: Generate OAuth Credentials
1. Go to the **Credentials** tab and create an **OAuth client ID** for a **Web application**.
2. Under **Authorized redirect URIs**, add the callback URI:
   - `http://127.0.0.1:8000/admin/integrations/google/callback`
3. Click **Create** and download the `.json` file.
4. Upload this file in the Admin or Staff Portal under the Google Search Console integration section.

---

## Running Tests

Automated testing is configured to run using an in-memory SQLite database (`:memory:`) automatically. To run the automated feature tests, execute:
```bash
php artisan test
```
This will verify route redirections, admin-only authentication checks, and layout loads.
