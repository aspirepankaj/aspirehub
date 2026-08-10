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

---


\Livewire\Livewire::component('your-component-name', \App\Modules\Path\To\YourComponent::class);

## Running Tests

Automated testing is configured to run using an in-memory SQLite database (`:memory:`) automatically. To run the automated feature tests, execute:
```bash
php artisan test
```
This will verify route redirections, admin-only authentication checks, and layout loads.
