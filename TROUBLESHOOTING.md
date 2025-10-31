# Troubleshooting Guide - NV3 Vegan Database

## Quick Diagnostics

Run these commands to check your setup:

```bash
# 1. Check if database exists
mysql -u root -p -e "USE nv3_vegan_db; SELECT COUNT(*) FROM user;"

# 2. Check migrations
php yii migrate --interactive=0

# 3. List users
php yii admin/list-users

# 4. Test backend server
php yii serve --docroot=backend/web --port=8080
# Visit: http://localhost:8080

# 5. Test frontend server
php yii serve --docroot=frontend/web --port=8081
# Visit: http://localhost:8081
```

## Common Frontend Issues

### Issue 1: "Page not found" (404) on /ingredient or /compare

**Symptoms:**
- Homepage works but `/ingredient` shows 404
- `/compare` shows 404
- Only `/site/...` URLs work

**Cause:** Pretty URLs not working or routes not configured

**Solution:**

1. **If using PHP built-in server:**
   ```bash
   # This is the CORRECT way for development:
   php yii serve --docroot=frontend/web --port=8081

   # NOT this (will cause 404s):
   php -S localhost:8081 -t frontend/web
   ```

2. **If using Apache:**
   - Make sure `.htaccess` exists in `frontend/web/` (we created it)
   - Enable mod_rewrite: `sudo a2enmod rewrite`
   - Restart Apache: `sudo service apache2 restart`

3. **If using Nginx:**
   Add this to your server block:
   ```nginx
   location / {
       try_files $uri $uri/ /index.php?$args;
   }
   ```

### Issue 2: "Class 'frontend\controllers\IngredientController' not found"

**Cause:** Autoloader not updated or files not in correct location

**Solution:**
```bash
cd /path/to/NV3
composer dump-autoload
```

### Issue 3: "Invalid Route" or "Unable to resolve the request"

**Symptoms:**
- Error message mentions "Unable to resolve the request ingredient/index"
- Controller exists but can't be found

**Possible Causes & Solutions:**

**A. File permissions:**
```bash
chmod -R 755 frontend/controllers
chmod -R 755 frontend/models
chmod -R 755 frontend/views
```

**B. Namespace issues:**
Check that `frontend/controllers/IngredientController.php` has:
```php
namespace frontend\controllers;
```

**C. URL Manager not configured:**
Already fixed in `frontend/config/main.php`

### Issue 4: Blank page or white screen

**Causes:** PHP errors with display_errors off

**Solution:**

1. Check error logs:
   ```bash
   tail -f frontend/runtime/logs/app.log
   ```

2. Enable error display temporarily in `frontend/web/index.php`:
   ```php
   defined('YII_DEBUG') or define('YII_DEBUG', true);
   defined('YII_ENV') or define('YII_ENV', 'dev');
   ```

3. Check PHP error log:
   ```bash
   tail -f /var/log/php_errors.log
   # or
   tail -f /var/log/apache2/error.log
   ```

### Issue 5: "No data" on ingredient or comparison pages

**Symptoms:**
- Pages load but show "No ingredients found"
- Empty grids/lists

**Cause:** No data in database yet

**Solution:**

1. **Login to backend:**
   ```bash
   php yii serve --docroot=backend/web --port=8080
   ```
   Visit: http://localhost:8080
   Login: admin / admin123

2. **Create test ingredients:**
   - Go to: http://localhost:8080/ingredient/create
   - Add at least 2-3 ingredients with:
     - Name (required)
     - Category (required)
     - Protein, Calories, etc. (for comparisons)
     - Status: Published (important!)

3. **Generate comparisons:**
   - Go to: http://localhost:8080/comparison/generate
   - Select 2 ingredients
   - Click "Generate"
   - Publish the comparison

4. **Check frontend again:**
   http://localhost:8081/ingredient

### Issue 6: CSS/styling not loading

**Symptoms:**
- Page loads but looks unstyled
- Only plain HTML visible

**Causes & Solutions:**

**A. Assets not writable:**
```bash
chmod -R 777 frontend/web/assets
rm -rf frontend/web/assets/*
```

**B. Wrong base URL:**
Check `frontend/web/index.php` - should use relative paths

**C. Asset bundles not published:**
- Refresh the page (assets publish on first load)
- Clear cache: `rm -rf frontend/runtime/cache/*`

### Issue 7: Database connection errors

**Symptoms:**
- "SQLSTATE[HY000] [2002] Connection refused"
- "SQLSTATE[HY000] [1045] Access denied"

**Solutions:**

**A. MySQL not running:**
```bash
sudo service mysql start
# or
sudo systemctl start mysql
```

**B. Database doesn't exist:**
```bash
mysql -u root -p -e "CREATE DATABASE nv3_vegan_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**C. Wrong credentials:**
Edit `common/config/main-local.php`:
```php
'db' => [
    'dsn' => 'mysql:host=localhost;dbname=nv3_vegan_db',
    'username' => 'root',  // your MySQL username
    'password' => '',       // your MySQL password
],
```

**D. MySQL socket issue:**
Try specifying the socket:
```php
'dsn' => 'mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname=nv3_vegan_db',
```

### Issue 8: "Table 'nv3_vegan_db.user' doesn't exist"

**Cause:** Migrations not run

**Solution:**
```bash
php yii migrate
```

If it says "No new migrations found" but tables don't exist:
```bash
php yii migrate/down 0
php yii migrate
```

### Issue 9: Can't login to backend

**A. Wrong password:**
Default credentials:
- Username: `admin`
- Password: `admin123`

**B. User doesn't exist:**
```bash
# Check users
php yii admin/list-users

# Create admin if missing
php yii admin/setup

# Or create custom user
php yii admin/create-user myuser myemail@example.com mypassword
```

**C. Reset password:**
```bash
php yii admin/reset-password admin newpassword123
```

### Issue 10: Port already in use

**Error:** "Address already in use" or "Failed to listen on localhost:8080"

**Solution:**
```bash
# Find what's using the port
lsof -i :8080

# Kill the process
kill -9 <PID>

# Or use different ports
php yii serve --docroot=backend/web --port=8090
php yii serve --docroot=frontend/web --port=8091
```

## Checking Your Setup

### Verify database tables exist:
```bash
mysql -u root -p nv3_vegan_db -e "SHOW TABLES;"
```

Expected tables:
- migration
- user
- media
- ingredients
- comparisons
- products

### Verify admin user exists:
```bash
mysql -u root -p nv3_vegan_db -e "SELECT id, username, email, status FROM user;"
```

Should show at least one user with status = 10 (active)

### Verify frontend controllers exist:
```bash
ls -la frontend/controllers/
```

Should show:
- SiteController.php
- IngredientController.php
- CompareController.php

### Verify URL manager is enabled:
```bash
grep -A 5 "urlManager" frontend/config/main.php
```

Should show `'enablePrettyUrl' => true`

## Still Having Issues?

### Get detailed error information:

1. **Check application logs:**
   ```bash
   tail -n 50 frontend/runtime/logs/app.log
   tail -n 50 backend/runtime/logs/app.log
   ```

2. **Enable debug mode:**
   In `frontend/web/index.php` and `backend/web/index.php`:
   ```php
   defined('YII_DEBUG') or define('YII_DEBUG', true);
   defined('YII_ENV') or define('YII_ENV', 'dev');
   ```

3. **Test individual components:**
   ```bash
   # Test if Yii works
   php yii

   # Test database connection
   php yii migrate --interactive=0

   # Test user model
   php yii admin/list-users
   ```

4. **Check PHP version:**
   ```bash
   php -v
   ```
   Should be 8.0 or higher

5. **Check composer dependencies:**
   ```bash
   composer diagnose
   ```

### Report the issue with:
- Error message from logs
- Which URL you're accessing
- What you expected vs what you see
- Your PHP version
- Your MySQL version
- Web server (Apache/Nginx/built-in)

## Quick Reset (Nuclear Option)

If everything is broken and you want to start fresh:

```bash
# 1. Drop and recreate database
mysql -u root -p -e "DROP DATABASE IF EXISTS nv3_vegan_db; CREATE DATABASE nv3_vegan_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Run migrations
php yii migrate --interactive=0

# 3. Create admin
php yii admin/setup

# 4. Clear all caches
rm -rf frontend/runtime/cache/*
rm -rf backend/runtime/cache/*
rm -rf frontend/web/assets/*
rm -rf backend/web/assets/*

# 5. Regenerate autoloader
composer dump-autoload

# 6. Start fresh
./start.sh
```

## Success Checklist

✅ Database `nv3_vegan_db` exists
✅ All migrations run successfully
✅ Admin user exists (check with `php yii admin/list-users`)
✅ Backend accessible at http://localhost:8080
✅ Frontend accessible at http://localhost:8081
✅ Can login to backend with admin/admin123
✅ Can create ingredients in backend
✅ Ingredients appear in frontend at /ingredient
✅ Can generate comparisons in backend
✅ Comparisons appear in frontend at /compare

If all boxes are checked, your installation is working! 🎉
