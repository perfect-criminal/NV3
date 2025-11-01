# NV3 Vegan Database - Setup Guide

## Prerequisites
- PHP 8.0+
- MySQL 5.7+ or MariaDB
- Composer (already installed)

## Step 1: Create Database

```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE nv3_vegan_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Create user (optional, for production)
CREATE USER 'nv3_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON nv3_vegan_db.* TO 'nv3_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## Step 2: Update Database Configuration

If you created a specific user, update `common/config/main-local.php`:

```php
'db' => [
    'class' => \yii\db\Connection::class,
    'dsn' => 'mysql:host=localhost;dbname=nv3_vegan_db',
    'username' => 'nv3_user',  // or 'root'
    'password' => 'your_password',  // or ''
    'charset' => 'utf8mb4',
],
```

## Step 3: Run Migrations

```bash
cd /path/to/NV3
php yii migrate
```

This will create all tables:
- user
- media
- ingredients
- comparisons
- products

## Step 4: Create Admin User

### Option A: Using Yii Console (Recommended)

```bash
# Create a console command to add admin user
php yii migrate --migrationPath=@yii/rbac/migrations

# Then use the signup functionality or create directly:
php yii
```

### Option B: Create Admin User Manually

Run this SQL after migrations:

```sql
USE nv3_vegan_db;

-- Insert admin user
-- Password: admin123 (hashed with Yii2's default security)
INSERT INTO `user` (
    `username`,
    `auth_key`,
    `password_hash`,
    `email`,
    `status`,
    `created_at`,
    `updated_at`
) VALUES (
    'admin',
    'test100key',
    '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3lO',  -- admin123
    'admin@nv3.local',
    10,  -- STATUS_ACTIVE
    UNIX_TIMESTAMP(),
    UNIX_TIMESTAMP()
);
```

### Option C: Use Signup Page (Easiest)

1. Start the application:
   ```bash
   php yii serve --docroot=backend/web --port=8080
   ```

2. Navigate to: `http://localhost:8080/site/signup`

3. Create admin account through the form

## Step 5: Access the Application

### Backend (Admin Panel)
```bash
php yii serve --docroot=backend/web --port=8080
```
Access: `http://localhost:8080`

### Frontend (Public Site)
```bash
php yii serve --docroot=frontend/web --port=8081
```
Access: `http://localhost:8081`

## Fixing Common Frontend Issues

### Issue 1: "Class not found" errors
**Solution:** Make sure you're in the correct directory and autoloader is working:
```bash
cd /path/to/NV3
composer dump-autoload
```

### Issue 2: 404 errors or routing issues
**Solution:** Check web server configuration. For development:
```bash
# Backend
php yii serve --docroot=backend/web --port=8080

# Frontend
php yii serve --docroot=frontend/web --port=8081
```

### Issue 3: Database connection errors
**Solution:**
1. Verify database exists: `mysql -u root -p nv3_vegan_db`
2. Check credentials in `common/config/main-local.php`
3. Test connection: `php yii migrate --interactive=0`

### Issue 4: Permission errors
**Solution:**
```bash
chmod -R 777 backend/runtime
chmod -R 777 backend/web/assets
chmod -R 777 frontend/runtime
chmod -R 777 frontend/web/assets
chmod -R 777 console/runtime
```

## Quick Start (After DB Setup)

```bash
# 1. Run migrations
php yii migrate

# 2. Create admin user
php yii admin/setup

# 3. Seed sample data (12 ingredients + 5 comparisons)
php yii seed/all

# 4. Start servers (or use ./start.sh)
php yii serve --docroot=backend/web --port=8080 &
php yii serve --docroot=frontend/web --port=8081 &

# 5. Access the sites
# Backend:  http://localhost:8080 (login: admin / admin123)
# Frontend: http://localhost:8081
```

**Alternative - Manual Setup:**
```bash
# Skip seeding and create data manually
# 1. Login to backend at http://localhost:8080
# 2. Create ingredients at /ingredient/create
# 3. Generate comparisons at /comparison/generate
# 4. View frontend at http://localhost:8081
```

## Seeding Sample Data (Optional but Recommended!)

We've created a comprehensive data seeder to populate your database with beautiful sample ingredients and comparisons!

### Quick Seed Everything

```bash
php yii seed/all
```

This will create:
- 12 realistic vegan ingredients (Tofu, Quinoa, Chickpeas, Almonds, Spinach, etc.)
- 5 AI-generated comparisons
- All with complete nutrition data, descriptions, and metadata

### Individual Commands

```bash
# Seed only ingredients
php yii seed/ingredients

# Seed only comparisons
php yii seed/comparisons

# Generate specific number of comparisons
php yii seed/comparisons 10
```

### What You Get

Each ingredient includes:
- Complete nutrition data (90+ fields)
- Detailed descriptions
- Cooking methods and tips
- Health benefits
- Sustainability scores
- Dietary flags (gluten-free, nut-free, etc.)

**See full documentation in [DATA_SEEDING.md](DATA_SEEDING.md)**

### Manual Entry

You can also manually add ingredients through the backend:
`http://localhost:8080/ingredient/create`

## Troubleshooting

### Can't access backend
- Make sure you're running the server: `php yii serve --docroot=backend/web --port=8080`
- Check if port 8080 is available: `lsof -i :8080`

### Frontend shows blank page
- Check error logs in `frontend/runtime/logs/app.log`
- Enable debug mode in `frontend/web/index.php` (already enabled in dev environment)
- Make sure you're accessing through the server: `http://localhost:8081` not `file://`

### Database connection failed
- Verify MySQL is running: `sudo service mysql status`
- Test connection: `mysql -u root -p nv3_vegan_db`
- Check credentials in `common/config/main-local.php`

## What Frontend URLs Should Work

After setup, these frontend URLs should work:
- `/` - Homepage
- `/ingredient` - Browse ingredients
- `/ingredient/category/protein` - Browse proteins
- `/ingredient/search` - Advanced search
- `/ingredient/finder` - Comparison finder
- `/compare` - Browse comparisons

These require data to be created in backend first!

## Contact

If you encounter issues, provide:
1. Error message from logs (`frontend/runtime/logs/app.log` or `backend/runtime/logs/app.log`)
2. Which URL you're accessing
3. What you expected vs what you see
