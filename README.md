# 🏘️ Barangay Resident Management System

<div align="center">
  
  ![Laravel](https://img.shields.io/badge/Laravel-10-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
  ![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)
  ![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
  ![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
  ![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

  **A Modern, Beautiful Web-Based System for Barangay Management**
  
  *Barangay Matina Pangi, Davao City*
  
</div>

---

## 📋 Overview

The **Barangay Resident Management System** is a web-based system designed to help barangay officials efficiently manage resident records, households, census, and community services. Built with **Laravel**, **Bootstrap 5**, and **MySQL (XAMPP)**, it provides a user-friendly dashboard with stunning modern design for secretaries and staff.

## ⚙️ Features

### 👨‍👩‍👧 Resident & Household Management
- ✅ Add, update, and view residents and their households, including extended families
- ✅ Solo resident registration (for residents living alone)
- ✅ Household head registration with automatic member forms
- ✅ New family head registration (married children in same household)
- ✅ Dynamic form generation based on household size
- ✅ Auto-fill shared household details (electric, rent status)
- ✅ Unique IDs for households (HH-xxxx) and residents (RES-xxxx)
- ✅ Beautiful profile pages with avatar initials and gradient headers

### 🗳️ Voter Status Tracking
- ✅ Automatically determines voter eligibility based on age
- ✅ SK (Sangguniang Kabataan) and regular voter tracking
- ✅ Precinct number management

### 📊 Census Dashboard
- ✅ **Modern Dashboard Design** with gradient stat cards
- ✅ Total residents, households, and population statistics
- ✅ Gender distribution with visual progress bars
- ✅ Age distribution breakdown (Children, Teens, Adults, Seniors)
- ✅ Special categories tracking (PWD, Senior Citizens, 4Ps, Voters)
- ✅ Quick actions panel for common tasks
- ✅ Recent residents table with avatars

### 📜 Certificate Management
- ✅ Issue barangay certificates (Indigency, Residency, Clearance, etc.)
- ✅ Beautiful form design with sectioned layout
- ✅ Certificate tracking and status management
- ✅ Purpose and amount tracking

### 🏥 Health & Social Services
- ✅ Health records management
- ✅ Senior citizen health monitoring
- ✅ PWD support tracking
- ✅ Government assistance programs (4Ps)
- ✅ Calamity assistance tracking

### 🔐 Role-Based Access Control
- ✅ **Secretary**: Full control - approve, add, manage all residents
- ✅ **Staff**: Can register and add residents (awaits approval)
- ✅ Approval workflow system
- ✅ Audit trail for all operations

### 🏠 Purok & Address Selection
- ✅ Dropdown selection for locations to avoid manual typing
- ✅ Organized by purok for easy management

### 📁 Archiving & Reporting
- ✅ View archived residents
- ✅ Export census data to PDF/Excel
- ✅ Comprehensive reporting system

### 💚 Modern UI/UX Design
- ✨ **Stunning gradient designs** throughout the system
- ✨ **Smooth animations** and hover effects
- ✨ **Responsive layout** - works on all devices
- ✨ **Clean interface** with emerald green theme
- ✨ **Avatar initials** for all residents
- ✨ **Color-coded badges** for status and categories
- ✨ **Enhanced sidebar** with filled icons and chevron indicators
- ✨ **Beautiful cards** with shadows and gradients
- ✨ **Professional typography** with Poppins and Inter fonts

## 🛠️ Tech Stack

| Technology | Purpose |
|------------|---------|
| **Framework** | Laravel 10 |
| **Frontend** | Bootstrap 5 + Custom CSS with Gradients |
| **Database** | MySQL (via XAMPP) |
| **Language** | PHP 8.1+, Blade Templates |
| **PDF Export** | DomPDF |
| **Excel Export** | Maatwebsite Excel |
| **Authentication** | Laravel Sanctum |
| **Tools** | phpMyAdmin, VS Code / Windsurf |

---

## ⚡ Installation Guide

### Prerequisites
- ✅ PHP 8.1 or higher
- ✅ MySQL (XAMPP)
- ✅ Composer
- ✅ Node.js & NPM (optional, for asset compilation)

### 📥 Setup Steps

#### 1️⃣ Clone this repository
```bash
git clone https://github.com/yourusername/barangay-matina-pangi.git
```

#### 2️⃣ Go to the project folder
```bash
cd barangay-matina-pangi
```

#### 3️⃣ Install dependencies
```bash
composer install
npm install
```

#### 4️⃣ Create .env file
```bash
cp .env.example .env
```

Configure your `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pangi
DB_USERNAME=root
DB_PASSWORD=
```

#### 5️⃣ Generate application key
```bash
php artisan key:generate
```

#### 6️⃣ Create database
- Open phpMyAdmin: http://localhost/phpmyadmin
- Create a database named `pangi`

#### 7️⃣ Run migrations
```bash
php artisan migrate
```

#### 8️⃣ Seed initial data
```bash
php artisan db:seed
```

**Default Accounts Created:**
- **Secretary**: `secretary@pangi.gov` / `password`
- **Staff**: `staff@pangi.gov` / `password`

#### 9️⃣ Run the project
```bash
php artisan serve
```

**Visit:** http://localhost:8000

Or access via XAMPP: http://localhost/barangay-matina-pangi/public

---

## 👥 User Roles

| Role | Description |
|------|-------------|
| **Secretary** | Full control: approve, add, and manage residents. Can export data and generate reports. |
| **Staff** | Can register and add residents (awaits approval). Read-only access to most features. |

---

## 📸 Logo

Barangay logo is saved in the `public/` folder and automatically displayed on the dashboard and landing page.

---

## 🎨 Design Highlights

This system features a **complete modern redesign** with:

- 💎 **Premium Gradient Designs** - Beautiful emerald green theme throughout
- ✨ **Smooth Animations** - Hover effects, transitions, and micro-interactions
- 🎯 **Stat Cards** - 8 gradient cards with icons showing key metrics
- 👤 **Avatar Initials** - Every resident gets a personalized avatar
- 🎨 **Color-Coded Badges** - Status indicators with semantic colors
- 📊 **Visual Charts** - Gender and age distribution with progress bars
- 🎭 **Enhanced Sidebar** - Filled icons with chevron indicators
- 💫 **Glass-morphism Effects** - Modern blur and transparency
- 📱 **Fully Responsive** - Works beautifully on all devices

---

## 🔒 Security

- ✅ Role-based access control
- ✅ Audit trail logging
- ✅ Secure authentication
- ✅ No public registration (admin-created accounts only)
- ✅ CSRF protection
- ✅ SQL injection prevention

---

## 💬 Developer

**Maintained by:** dre143

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

**Copyright © 2025 dre143**

---

<div align="center">
  
  **Made with ❤️ for Barangay Matina Pangi**
  
  *Building a connected community — one record at a time.*
  
</div>
