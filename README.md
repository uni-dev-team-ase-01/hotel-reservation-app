# 🏨 Hotel Reservation System – Google Jules Dropdown Fix

## 👥 Team Members
- Ramisha Gimhama
- Pasindu Lakmal
- Jithma Perera

---

## 🧩 Project Overview
This hotel reservation system allows users to:
- Search for hotels by location
- Select room types dynamically
- Book rooms seamlessly online with a user-friendly interface

Our frontend uses interactive dropdowns powered by **Choices.js** to enhance user experience.
During development, we encountered a technical issue related to the Google Jules integration affecting these dropdowns.
This README explains the issue, how we resolved it, and demonstrates the working system.

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

### Presentation Introduction Script
> Hello everyone, I’m Ramisha. I’ll start by walking you through the homepage view of our hotel reservation system.
> After that, I’d like to invite Pasindu to explain the dropdown features and the issue we faced with Google Jules integration.

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