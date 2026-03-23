# DSL Battery System - Laravel Setup

## Project Overview
This is a Laravel 10-based Barcode Management System for DSL Battery warehouse operations. It includes user authentication with role-based access control and database structure for managing battery items, GRN (Good Received Notes), item issues, and returns.

---

## Installation & Setup

### 1. Database Configuration
The SAP HANA connection is configured in `.env` with:
```
DB_CONNECTION=hana
HANA_HOST=192.168.4.80
HANA_PORT=30015
HANA_DATABASE=TEST_DSL
HANA_USERNAME=your_hana_user
HANA_PASSWORD=your_hana_password
HANA_SCHEMA=TEST_DSL
```

### 2. Migrations & Seeding
The database has already been created and migrated with:
- **Users Table** - With role-based access (admin, warehouse_manager, operations_officer)
- **Battery System Tables:**
  - `ybimmst` - Item Master (battery items catalog)
  - `ybgrn` - Good Received Note (GRN management)
  - `ybissue` - Item Issue (inventory movements)
  - `ybismanu` - Manual Issue (manual item issues)
  - `ybgrtn` - GRN Return (return management)

### 3. Demo Users
Three demo users have been created. Use these credentials to login:

| Email | Password | Role |
|-------|----------|------|
| admin@dsl.com | password | Admin |
| warehouse@dsl.com | password | Warehouse Manager |
| operations@dsl.com | password | Operations Officer |

---

## Project Structure

```
BA_BARCODE_V1/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php      # Authentication logic
│   │   │   ├── DashboardController.php # Dashboard logic
│   │   │   ├── BarcodeController.php   # Barcode operations
│   │   │   └── ItemMasterController.php # Item management
│   │   └── Middleware/
│   │       └── CheckRole.php           # Role-based access middleware
│   └── Models/
│       └── User.php                    # User model with role support
├── database/
│   ├── migrations/                     # All table schemas
│   └── seeders/
│       └── UserSeeder.php              # Demo user creation
├── resources/
│   └── views/
│       ├── auth/
│       │   └── login.blade.php         # Login form
│       └── dashboard/
│           └── index.blade.php         # Dashboard view
├── routes/
│   └── web.php                         # Route definitions
└── .env                                # Environment configuration
```

---

## Running the Application

### Start the Development Server
```bash
php artisan serve
```

The application will be available at: **http://localhost:8000**

### Access Points
- **Login Page:** http://localhost:8000/login
- **Dashboard:** http://localhost:8000/dashboard (after login)

---

## User Roles & Permissions

### 1. **Admin**
- Full system access
- Can manage all features
- User and system administration

### 2. **Warehouse Manager**
- Manages warehouse operations
- GRN processing
- Item receipt and returns
- Barcode management

### 3. **Operations Officer**
- General operational tasks
- Item issue processing
- Barcode scanning and viewing
- Basic reporting

---

## Features (Implemented)

✅ **Authentication System**
- Secure login with email & password
- Role-based access control
- Session management
- Logout functionality

✅ **Database Structure**
- All 5 battery system tables created
- Proper relationships and constraints
- Column definitions as per DSL specifications

✅ **Dashboard**
- User-friendly interface
- Role-specific view
- Menu for all system features

⏳ **Coming Soon**
- View Barcode functionality
- Item Master management
- GRN management
- Item Issue tracking
- Battery Return processing
- GRN Return handling
- Manual Issue creation
- Barcode Approval workflow
- Reports & Analytics

---

## Key Files

### Controllers
- **AuthController.php** - Handles login/logout
- **DashboardController.php** - Dashboard display
- **BarcodeController.php** - Barcode operations (to be implemented)
- **ItemMasterController.php** - Item management (to be implemented)

### Views
- **auth/login.blade.php** - Professional login interface
- **dashboard/index.blade.php** - Dashboard with role information

### Routes
- GET `/login` - Login page
- POST `/login` - Process login
- GET `/dashboard` - Dashboard (requires authentication)
- POST `/logout` - Logout

---

## Database Tables

### 1. YBIMMST (Item Master)
```
- itmcode (PK): Item code
- itmnme: Item name
- itmmod: Item model
- itmamp: Item amperage
- f_war: Factory warranty (months)
- pa_war: Product warranty (months)
- remark: Remarks
- prphase: Product phase
- brand: Brand name
```

### 2. YBGRN (Good Received Note)
```
- gbarcode (PK): Barcode
- gitmcode: Item code
- gdte: GRN date
- grnno: GRN number
- gcrtusr: Created by user
- gcrtdtme: Created datetime
- gremark: Remarks
- gchprt: Printed flag
- gchact: Active flag
- whscode: Warehouse code
- chwhsprt: Warehouse print flag
```

### 3. YBISSUE (Item Issue)
```
- ibarcode (PK): Barcode
- invno: Invoice number
- itmnme: Item name
- itmmod: Item model
- itmamp: Item amperage
- f_war: Factory warranty
- pa_war: Product warranty
- isudtme: Issue datetime
- iremark: Issue remarks
- ichsale: Sold flag
- saledtme: Sale datetime
- ichapr: Approved flag
- iaprdte: Approval date
- fncusnm: Final customer name
- fncustp: Final customer type
- location: Location
```

### 4. YBISMANU (Manual Issue)
Similar structure to YBISSUE for manual item issues.

### 5. YBGRTN (GRN Return)
```
- gbarcode (PK): Barcode
- gitmcode: Item code
- gdte: Return date
- grnno: GRN number
- gcrtusr: Created by user
- gcrtdtme: Created datetime
- gremark: Remarks
- reason: Return reason
- rtndtme: Return datetime
- whscode: Warehouse code
```

---

## Next Steps

1. **Implement Barcode View Feature**
   - Search functionality by barcode
   - Display item details from YBIMMST
   - Show warranty info from YBISSUE/YBISMANU

2. **Implement Item Master Management**
   - Add new items
   - Edit item information
   - Generate and print barcodes
   - Search items

3. **Implement GRN Management**
   - Create new GRN records
   - Barcode printing
   - GRN return processing

4. **Implement Item Issues**
   - Issue items from inventory
   - Manual issue creation
   - Track inventory movements

5. **Add Barcode Scanning**
   - Integrate barcode input
   - Real-time item lookup
   - Automated data population

6. **Reporting & Analytics**
   - Inventory reports
   - Transaction history
   - Warranty tracking

---

## Troubleshooting

### Database Connection Issues
```bash
# Test database connection
php artisan tinker
DB::connection()->getPdo();
```

### Clear Application Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Reset Database
```bash
php artisan migrate:refresh --seed
```

---

## Support
For issues or questions regarding the system setup, please refer to the Laravel documentation at https://laravel.com/docs

---

**Last Updated:** January 21, 2026
**Laravel Version:** 10.50.0
**PHP Version:** 8.0+
