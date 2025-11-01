# Sample Data Seeding Guide

This guide explains how to populate your NV3 database with sample ingredients and comparisons to see the beautiful UI in action.

## Overview

The `SeedController` provides console commands to quickly populate your database with realistic vegan ingredient data and AI-generated comparisons.

## Prerequisites

- Database must be configured and migrations must be run
- MySQL server must be running
- Admin user should be created (for backend access)

## Sample Data Included

### 12 Sample Ingredients

1. **Tofu** - Versatile soy-based protein
2. **Quinoa** - Complete protein grain
3. **Chickpeas** - Protein-rich legumes
4. **Almonds** - Nutrient-dense tree nuts
5. **Spinach** - Iron-rich leafy green
6. **Oat Milk** - Creamy dairy alternative
7. **Tempeh** - Fermented soy protein
8. **Brown Rice** - Whole grain staple
9. **Lentils** - Quick-cooking legumes
10. **Nutritional Yeast** - B12-fortified seasoning
11. **Sweet Potato** - Beta-carotene rich vegetable
12. **Chia Seeds** - Omega-3 rich superfood

Each ingredient includes:
- Complete nutrition data (calories, protein, fat, carbs, fiber, vitamins, minerals)
- Detailed descriptions and summaries
- Cooking methods and common uses
- Health benefits
- Dietary flags (gluten-free, nut-free, etc.)
- Sustainability scores
- Storage tips
- Allergen information

### 5 Sample Comparisons

Interesting head-to-head comparisons:
1. Tofu vs Tempeh
2. Quinoa vs Brown Rice
3. Chickpeas vs Lentils
4. Almonds vs Chia Seeds
5. Oat Milk vs Almond Milk (if available)

Each comparison includes:
- AI-generated summary and analysis
- Key differences table
- Nutrition comparison
- Recommendations
- Conclusion

## Commands

### Seed All Data (Recommended)

Populate both ingredients and comparisons:

```bash
php yii seed/all
```

This will:
1. Create 12 sample ingredients
2. Generate 5 comparison articles
3. Display success messages and next steps

### Seed Only Ingredients

If you only want to add ingredients:

```bash
php yii seed/ingredients
```

### Seed Only Comparisons

Generate comparisons between existing ingredients:

```bash
php yii seed/comparisons
```

You can specify how many comparisons to generate:

```bash
php yii seed/comparisons 10
```

## Running the Seeder

### Step 1: Ensure Database is Ready

```bash
# Check database configuration
php yii migrate

# Verify admin user exists
php yii admin/list-users
```

### Step 2: Run the Seeder

```bash
# Seed all sample data
php yii seed/all
```

Expected output:
```
Seeding sample ingredients...
  ✓ Created 'Tofu'
  ✓ Created 'Quinoa'
  ✓ Created 'Chickpeas'
  ...
Successfully created 12 ingredients!

Generating sample comparisons...
  ✓ Generated 'Tofu vs Tempeh: Complete 2025 Comparison'
  ✓ Generated 'Quinoa vs Brown Rice: Complete 2025 Comparison'
  ...
Successfully generated 5 comparisons!

=================================
Sample data seeding complete!
=================================
Visit your site to see the beautiful cards in action:
  Frontend: http://localhost:8081
  Backend:  http://localhost:8080
```

### Step 3: Start the Servers

```bash
# Start both frontend and backend
./start.sh
```

Or manually:

```bash
# Backend (admin panel)
php yii serve --docroot=backend/web --port=8080 &

# Frontend (public site)
php yii serve --docroot=frontend/web --port=8081 &
```

### Step 4: Explore the UI

**Frontend (http://localhost:8081):**
- Browse ingredients with beautiful cards
- See the category grid
- View ingredient details
- Explore comparisons
- Test the search functionality

**Backend (http://localhost:8080):**
- View the admin dashboard with statistics
- See recent activity feeds
- Browse and manage ingredients
- Review and edit comparisons

## Customization

### Add Your Own Ingredients

You can easily add more ingredients by editing `console/controllers/SeedController.php` and adding to the `$ingredients` array in the `actionIngredients()` method.

Example:
```php
[
    'name' => 'Your Ingredient',
    'slug' => 'your-ingredient',
    'category' => 'protein', // or grain, vegetable, fruit, nut, seed, legume, milk_alternative, other
    'summary' => 'Short description...',
    'description' => 'Longer description...',
    'calories' => 100,
    'protein' => 10.0,
    // ... more fields
    'status' => 'published',
    'published_at' => date('Y-m-d H:i:s'),
],
```

### Re-running the Seeder

The seeder is **idempotent** - it checks if ingredients already exist before creating them. This means:

✅ Safe to run multiple times
✅ Won't create duplicates
✅ Only creates missing ingredients

If you want to start fresh:

```bash
# WARNING: This deletes all data!
php yii migrate/fresh

# Then re-run seeder
php yii seed/all
```

## Troubleshooting

### "No such file or directory" Database Error

**Cause:** MySQL is not running or database connection settings are incorrect.

**Solution:**
1. Check MySQL is running: `mysql.server status` (Mac) or `sudo service mysql status` (Linux)
2. Verify database exists: `mysql -u root -p` then `SHOW DATABASES;`
3. Check `common/config/main-local.php` database settings

### "Need at least 2 published ingredients"

**Cause:** Not enough ingredients to create comparisons.

**Solution:**
```bash
# First seed ingredients
php yii seed/ingredients

# Then seed comparisons
php yii seed/comparisons
```

### "Failed to create ingredient"

**Cause:** Validation error or missing required field.

**Solution:** Check the error message for details. Common issues:
- Invalid category value
- Missing required fields
- Duplicate slug

## What's Next?

After seeding sample data:

1. **Explore the Frontend**: Browse ingredients, view comparisons, test search
2. **Check the Admin Panel**: Review dashboard stats, manage content
3. **Create Your Own Data**: Add real ingredients using the backend
4. **Generate More Comparisons**: Use the batch generate feature
5. **Customize the UI**: Adjust CSS to match your brand

## Notes

- All nutrition values are per 100g
- Sustainability scores range from 1-10 (higher is better)
- Carbon footprint is in kg CO2e per kg of product
- All sample data is based on real nutrition information from USDA and other reliable sources

Enjoy your beautiful vegan ingredient database! 🌱
