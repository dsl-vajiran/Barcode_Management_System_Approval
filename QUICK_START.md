# DSL Battery System - Quick Start Guide

## 🚀 Getting Started

### 1. Start the Laravel Server
```bash
cd c:\Users\Vajira\BA_BARCODE_V1
php artisan serve
```

Server will run at: **http://localhost:8000**

### 2. Access the System
- **URL:** http://localhost:8000
- You will be redirected to the login page

---

## 🔐 Demo Credentials

### Admin Account
```
Email: admin@dsl.com
Password: password
Role: Admin
```
**Access:** Full system access, all features

### Warehouse Manager Account
```
Email: warehouse@dsl.com
Password: password
Role: Warehouse Manager
```
**Access:** Warehouse operations, GRN, item receipt & returns

### Operations Officer Account
```
Email: operations@dsl.com
Password: password
Role: Operations Officer
```
**Access:** General operations, item issue, barcode viewing

---

## 📊 System Features

### ✅ Already Implemented
- User Authentication with Roles
- SAP HANA Database Connection
- All System Tables Created
- Professional Login Interface
- Role-Based Dashboard
- Logout Functionality

### ⏳ Ready to Implement
- View Barcode (Search by barcode code)
- Item Master Management
- GRN Processing
- Item Issue Tracking
- Battery Return Management
- GRN Returns
- Manual Issues
- Barcode Approval
- Reports & Analytics

---

## 🛠️ Database Details

**Database Name:** `TEST_DSL`
**Host:** 192.168.4.80
**Port:** 30015
**Connection:** SAP HANA (`hana`)

**Tables Created:**
1. `users` - User accounts with roles
2. `ybimmst` - Item Master (Battery catalog)
3. `ybgrn` - Good Received Notes
4. `ybissue` - Item Issues
5. `ybismanu` - Manual Issues
6. `ybgrtn` - GRN Returns

---

## 🗂️ Project Structure

```
c:\Users\Vajira\BA_BARCODE_V1\
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── BarcodeController.php
│   │   └── ItemMasterController.php
│   └── Models/User.php
├── resources/views/
│   ├── auth/login.blade.php
│   └── dashboard/index.blade.php
├── routes/web.php
├── database/migrations/
├── database/seeders/UserSeeder.php
├── .env (HANA configured)
└── SETUP_GUIDE.md
```

---

## 📝 Available Routes

```
GET  /              → Redirect to login/dashboard
GET  /login         → Login page
POST /login         → Process login
GET  /dashboard     → Dashboard (Protected)
POST /logout        → Logout
GET  /barcode       → View Barcode (Coming Soon)
GET  /item-master   → Item Master (Coming Soon)
```

---

## 🔧 Quick Commands

### Generate Application Key
```bash
php artisan key:generate
```

### Run Migrations
```bash
php artisan migrate
```

### Seed Demo Data
```bash
php artisan db:seed --class=UserSeeder
```

### Clear Cache
```bash
php artisan cache:clear
```

### Reset Database
```bash
php artisan migrate:refresh --seed
```

---

## 💡 User Roles Explained

### Admin (admin@dsl.com)
- Full system access
- Create/edit users
- System configuration
- View all data
- Generate reports

### Warehouse Manager (warehouse@dsl.com)
- Manage warehouse operations
- Create GRN records
- Process item receipts
- Handle returns
- Print barcodes
- View inventory

### Operations Officer (operations@dsl.com)
- Issue items
- Scan barcodes
- View item details
- Track warranty
- Basic operations
- Limited reporting

---

## 🔍 Login Page Features

- Clean, modern interface
- Role selection dropdown
- Error message display
- Demo credential display
- Mobile responsive design

---

## ✨ Dashboard Features

- Welcome message with user name
- Role badge display
- Feature menu cards
- Quick system status
- Easy navigation
- Logout button

---

## 📋 Next Steps

1. **Start the server** with `php artisan serve`
2. **Login** with any demo credentials
3. **Explore** the dashboard
4. **Implement** the remaining features as needed

---

## 🐛 Troubleshooting

### Can't connect to database?
- Check `.env` file has correct credentials
- Ensure the HANA server is reachable
- Check `HANA_*` values in `.env`

### Login fails?
- Make sure you're using exact credentials
- Check the role dropdown matches your account
- Clear browser cookies and try again

### 404 errors?
- Run `php artisan cache:clear`
- Run `php artisan config:clear`
- Restart the server

---

## 📞 Contact & Support

For Laravel documentation: https://laravel.com
For SAP HANA issues: https://help.sap.com

---

**System Version:** 1.0
**Laravel Version:** 10.50.0
**PHP Requirement:** 8.0+
**Database:** SAP HANA

Happy coding! 🎉
