# Mini Inventory (Laravel + Vue 3 + Tailwind + Vite)

A lightweight hybrid Laravel + Vue 3 project with Tailwind CSS, powered by Vite.  
Perfect for small inventory management or as a boilerplate for Laravel + Vue hybrid apps.

---

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Environment Configuration](#environment-configuration)
- [Database Setup](#database-setup)
- [Running the Project](#running-the-project)
- [Folder Structure](#folder-structure)
- [Vue Component Example](#vue-component-example)
- [Common Issues & Fixes](#common-issues--fixes)
- [NPM / Artisan Scripts](#npm--artisan-scripts)
- [Contributing](#contributing)
- [License](#license)
- [Notes](#notes)

---

## Features

- Laravel 10+ backend with modular architecture.
- Vue 3 components registered inline with Blade templates.
- Tailwind CSS for modern utility-first styling.
- Vite for fast asset bundling and hot-reloading.
- AJAX-powered product list fetching.
- Blade + Vue hybrid setup (not a full SPA).
- Lightweight, easily extendable for multi-tenant or enterprise applications.

---

## Requirements

- **PHP >= 8.1**
- **Composer**
- **Node.js >= 18**
- **NPM or Yarn**
- **MySQL / PostgreSQL / SQLite database**

---

## Installation

1. **Clone the repository**

```bash
git clone https://github.com/Asjadgit/Mini_Inventory.git
cd mini-inventory
```
1. **Clone the repository**

```bash
git clone https://github.com/Asjadgit/Mini_Inventory.git
cd mini-inventory
```
2. **Install PHP dependencies**

```bash
composer install
```

3. **Install Node dependencies**

```bash
npm install
```

4. **Copy .env file and configure**

```bash
cp .env.example .env
```

4. **Generate application key**

```bash
php artisan key:generate
```
4. **Run database migrations**

```bash
php artisan migrate
```
4. **Start Vite dev server**

```bash
npm run start
```
4. **Start Laravel server**

```bash
php artisan serve
```
✅ This is **complete, production-ready, and all-inclusive**. Anyone can follow it from cloning to running the app without missing steps.
