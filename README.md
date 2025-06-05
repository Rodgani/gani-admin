# 🚀 Gani Admin

**Gani Admin** is a modern admin panel built using the [Laravel React Starter Kit](https://github.com/your-starter-kit-link), combining the power of Laravel, Inertia.js, and React. It includes essential features for building robust admin interfaces quickly.

## 🧩 Features

- 🔐 **Authentication** (login, registration, password reset)
- 👥 **User Management** (create, update, delete, assign roles)
- 🛡 **Role Management** (define roles and permissions)
- 🎯 **Menu Management** (define menus for front-end and attach available permissions)

## 🛠️ Tech Stack

| Layer         | Technology                |
|---------------|----------------------------|
| Backend       | Laravel 12 (PHP 8.4)       |
| Frontend      | React + Inertia.js         |
| Database      | PostgreSQL                 |
| Styling       | Tailwind CSS *(if used)*   |
| Auth System   | Based on Laravel Starter Kit |

## 📦 Getting Started

### Prerequisites

- PHP >= 8.4
- Composer
- Node.js and npm
- PostgreSQL

### Installation

```bash
# Clone the repository
git clone https://github.com/your-username/gani-admin.git
cd gani-admin

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Update your database credentials in .env
# Then run:
php artisan migrate --seed

# Build assets
npm run dev

or use the laravel sail
sail up -d
sail npm run dev
sail artisan m:fresh --seed

```

🧪 Extra usage

Use the following Artisan command to create Laravel classes inside your module:
```
php artisan make:module {type} {name} {module}

php artisan make:module controller UserController Blog
```
