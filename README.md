<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Hotel Reservation System

### Features
#### Customer
- Users can register and login to the system
- Search hotels - *TODO*
- Reserve rooms - *TODO*
- Able to make payment - *TODO*

#### Admin
- Manage users - *TODO*
- Manage hotels - *TODO*
- Manage payment - *TODO*

#### Hotel Clerk

#### Hotel Manager

#### Travel Company


## Installation

## Changes
#### Stack
Laravel, Livewire Volt with MySQL
- Customer panel (bootstrap, Laravel UI)
- Administration panels (filament)

Auth
- Two guards (web, admin)
- `CustomFilamentLogoutResponse` added
- Assign `customer` role to user on registration
- Restrict customers access to admin panel
- Restrict admin access to customer panel

Filament
- Added laravel permission plugin
- Added users plugin
- 

## Environment Variables

add your database connection to .env file
```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

add your Stipe values to .env file

```bash
STRIPE_KEY=your_publishable_key
STRIPE_SECRET=your_secret_key
STRIPE_WEBHOOK_SECRET=your_webhook_secret

```
create Stripe Sandbox

enable customer portal in Stripe

also, setup webhook for `payment_method.detached` and `payment_method.attached` events (destination : `/api/webhook/stripe`). `STRIPE_WEBHOOK_SECRET` can be find there.


## Scheduler
run Laravel scheduler

```bash
php artisan schedule:work
```

configured schedule jobs
- cancel reservations no payment method attached at 7pm