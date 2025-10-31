# Strategic Plan: Factual Vegan Database & Comparison Platform

## Vision Statement
Build a high-speed, SEO-optimized web platform that programmatically generates and displays authoritative, factual comparison articles on vegan ingredients and products using Generative AI, serving as the ultimate factual vegan database.

**Target Audience:** Beginners, Flexitarians, and health-conscious individuals seeking quick, unbiased facts about plant-based foods

---

## Current System Assessment ✅

### What We Have Built
- ✅ Modern CMS with content management
- ✅ AI content generation infrastructure
- ✅ SEO optimization (Schema.org, meta tags, Open Graph)
- ✅ Article display system with images
- ✅ Categories and tags system
- ✅ User authentication and roles
- ✅ Comment system
- ✅ Media management
- ✅ Performance tracking
- ✅ Analytics foundation

### Technology Stack
- **Framework:** Yii2 Advanced Template
- **Database:** MySQL with proper indexing
- **Frontend:** Bootstrap 5, responsive design
- **SEO:** Schema.org JSON-LD, sitemap ready
- **AI:** Queue-based generation system
- **CDN Ready:** Media table supports CDN URLs

---

## Phase 1: Database Foundation (High Priority) 🎯

### 1.1 Ingredient Database Schema

Create comprehensive ingredient database tables:

```sql
-- Ingredients Table (Core Database)
CREATE TABLE `ingredients` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `category` ENUM('protein', 'grain', 'vegetable', 'fruit', 'nut', 'seed', 'legume', 'milk_alternative', 'other') NOT NULL,
  `subcategory` VARCHAR(100) DEFAULT NULL,
  `common_names` JSON DEFAULT NULL, -- ["tofu", "bean curd", "doufu"]
  `scientific_name` VARCHAR(255) DEFAULT NULL,
  `origin` VARCHAR(255) DEFAULT NULL,

  -- Basic Info
  `description` TEXT,
  `summary` VARCHAR(500),
  `taste_profile` VARCHAR(255), -- "Mild, slightly nutty"
  `texture` VARCHAR(255), -- "Firm, soft, silky"

  -- Nutrition per 100g (standardized)
  `calories` DECIMAL(8,2) DEFAULT NULL,
  `protein` DECIMAL(8,2) DEFAULT NULL,
  `fat` DECIMAL(8,2) DEFAULT NULL,
  `carbs` DECIMAL(8,2) DEFAULT NULL,
  `fiber` DECIMAL(8,2) DEFAULT NULL,
  `sugar` DECIMAL(8,2) DEFAULT NULL,
  `sodium` DECIMAL(8,2) DEFAULT NULL,

  -- Vitamins & Minerals (% daily value)
  `vitamin_b12` DECIMAL(8,2) DEFAULT NULL,
  `vitamin_d` DECIMAL(8,2) DEFAULT NULL,
  `iron` DECIMAL(8,2) DEFAULT NULL,
  `calcium` DECIMAL(8,2) DEFAULT NULL,
  `zinc` DECIMAL(8,2) DEFAULT NULL,
  `omega3` DECIMAL(8,2) DEFAULT NULL,

  -- Additional nutrition data (JSON for flexibility)
  `nutrition_extended` JSON DEFAULT NULL,

  -- Health & Benefits
  `health_benefits` JSON DEFAULT NULL, -- ["High protein", "Rich in iron"]
  `allergens` JSON DEFAULT NULL, -- ["soy", "gluten"]
  `dietary_flags` JSON DEFAULT NULL, -- ["gluten-free", "soy-free", "nut-free"]

  -- Usage & Cooking
  `cooking_methods` JSON DEFAULT NULL, -- ["grilled", "baked", "raw"]
  `common_uses` JSON DEFAULT NULL, -- ["salads", "stir-fry", "smoothies"]
  `substitutes` JSON DEFAULT NULL, -- [{"id": 5, "name": "tempeh", "ratio": "1:1"}]
  `storage_tips` TEXT,
  `preparation_tips` TEXT,

  -- Sourcing & Sustainability
  `season` VARCHAR(255) DEFAULT NULL,
  `sustainability_score` TINYINT DEFAULT NULL, -- 1-10
  `environmental_impact` TEXT,
  `certifications` JSON DEFAULT NULL, -- ["organic", "fair-trade"]

  -- Cost & Availability
  `avg_price_per_kg` DECIMAL(8,2) DEFAULT NULL,
  `availability` ENUM('common', 'moderate', 'rare') DEFAULT 'common',

  -- Media & SEO
  `featured_image_id` INT(11) DEFAULT NULL,
  `gallery_images` JSON DEFAULT NULL, -- [image_id_1, image_id_2]
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_description` VARCHAR(500) DEFAULT NULL,
  `meta_keywords` VARCHAR(500) DEFAULT NULL,
  `schema_data` JSON DEFAULT NULL,

  -- AI & Data Quality
  `ai_generated` TINYINT(1) DEFAULT 0,
  `data_verified` TINYINT(1) DEFAULT 0,
  `verified_by` INT(11) DEFAULT NULL,
  `verified_at` TIMESTAMP NULL DEFAULT NULL,
  `sources` JSON DEFAULT NULL, -- ["USDA", "NIH", "study_link"]

  -- Stats & Engagement
  `view_count` INT(11) DEFAULT 0,
  `comparison_count` INT(11) DEFAULT 0, -- How many times used in comparisons
  `rating` DECIMAL(3,2) DEFAULT NULL, -- User ratings
  `rating_count` INT(11) DEFAULT 0,

  -- Status & Timestamps
  `status` ENUM('draft', 'published', 'archived') DEFAULT 'draft',
  `published_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_slug` (`slug`),
  KEY `idx_category` (`category`),
  KEY `idx_status` (`status`),
  KEY `idx_name` (`name`),
  FULLTEXT KEY `ft_search` (`name`, `description`, `summary`),
  CONSTRAINT `fk_ingredient_verified_by` FOREIGN KEY (`verified_by`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_ingredient_image` FOREIGN KEY (`featured_image_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Comparisons Table
CREATE TABLE `comparisons` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `ingredient_a_id` INT(11) NOT NULL,
  `ingredient_b_id` INT(11) NOT NULL,
  `slug` VARCHAR(500) NOT NULL UNIQUE, -- "tofu-vs-tempeh"
  `title` VARCHAR(500) NOT NULL, -- "Tofu vs Tempeh: Complete Comparison"

  -- Comparison Data
  `summary` TEXT, -- AI-generated summary
  `winner_category` VARCHAR(100) DEFAULT NULL, -- "protein", "overall", "cost"
  `comparison_data` JSON DEFAULT NULL, -- Structured comparison

  -- Content
  `introduction` TEXT,
  `conclusion` TEXT,
  `key_differences` JSON DEFAULT NULL,
  `recommendations` TEXT,

  -- SEO
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_description` VARCHAR(500) DEFAULT NULL,
  `schema_data` JSON DEFAULT NULL,

  -- AI & Quality
  `ai_generated` TINYINT(1) DEFAULT 0,
  `ai_model` VARCHAR(100) DEFAULT NULL,
  `generated_at` TIMESTAMP NULL DEFAULT NULL,
  `reviewed_by` INT(11) DEFAULT NULL,
  `reviewed_at` TIMESTAMP NULL DEFAULT NULL,

  -- Stats
  `view_count` INT(11) DEFAULT 0,
  `helpful_count` INT(11) DEFAULT 0,
  `not_helpful_count` INT(11) DEFAULT 0,

  -- Status
  `status` ENUM('draft', 'published', 'archived') DEFAULT 'draft',
  `published_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_slug` (`slug`),
  KEY `idx_ingredients` (`ingredient_a_id`, `ingredient_b_id`),
  KEY `idx_status` (`status`),
  KEY `idx_view_count` (`view_count`),
  CONSTRAINT `fk_comparison_ingredient_a` FOREIGN KEY (`ingredient_a_id`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_comparison_ingredient_b` FOREIGN KEY (`ingredient_b_id`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_comparison_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Product Database (for specific brands/products)
CREATE TABLE `products` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `ingredient_id` INT(11) DEFAULT NULL, -- Link to base ingredient
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `brand` VARCHAR(255) NOT NULL,
  `category` VARCHAR(100) NOT NULL,

  -- Product Info
  `description` TEXT,
  `price` DECIMAL(10,2) DEFAULT NULL,
  `currency` VARCHAR(10) DEFAULT 'USD',
  `package_size` VARCHAR(100) DEFAULT NULL, -- "500g", "1L"
  `barcode` VARCHAR(100) DEFAULT NULL,

  -- Nutrition (per serving)
  `serving_size` VARCHAR(100) DEFAULT NULL,
  `nutrition_data` JSON DEFAULT NULL,

  -- Availability
  `where_to_buy` JSON DEFAULT NULL, -- [{"store": "Whole Foods", "url": "..."}]
  `availability_region` JSON DEFAULT NULL, -- ["US", "EU", "Asia"]

  -- Ratings & Reviews
  `rating` DECIMAL(3,2) DEFAULT NULL,
  `rating_count` INT(11) DEFAULT 0,

  -- Media & SEO
  `featured_image_id` INT(11) DEFAULT NULL,
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_description` VARCHAR(500) DEFAULT NULL,
  `schema_data` JSON DEFAULT NULL,

  -- Status
  `status` ENUM('draft', 'published', 'discontinued') DEFAULT 'draft',
  `verified` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_slug` (`slug`),
  KEY `idx_ingredient` (`ingredient_id`),
  KEY `idx_brand` (`brand`),
  KEY `idx_category` (`category`),
  CONSTRAINT `fk_product_ingredient` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_product_image` FOREIGN KEY (`featured_image_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 1.2 Initial Ingredient Seeding

**Priority ingredients to add first (Top 50):**

**Proteins:**
- Tofu, Tempeh, Seitan, TVP, Edamame
- Chickpeas, Black Beans, Lentils, Kidney Beans
- Nutritional Yeast

**Milk Alternatives:**
- Almond Milk, Oat Milk, Soy Milk, Coconut Milk
- Cashew Milk, Rice Milk, Hemp Milk

**Grains:**
- Quinoa, Rice, Oats, Barley, Buckwheat

**Vegetables & Fruits:**
- Spinach, Kale, Broccoli, Sweet Potato
- Banana, Avocado, Berries

**Nuts & Seeds:**
- Almonds, Walnuts, Cashews, Chia Seeds, Flax Seeds

---

## Phase 2: Comparison Engine 🚀

### 2.1 Comparison Generator Features

**Automatic Comparison Generation:**
1. Select two ingredients
2. AI generates comprehensive comparison covering:
   - Nutrition breakdown with visual charts
   - Protein/calorie comparison
   - Cost analysis
   - Environmental impact
   - Taste & texture differences
   - Best use cases
   - Recommendations

### 2.2 Comparison Article Template

**Structure:**
```markdown
# [Ingredient A] vs [Ingredient B]: Complete 2025 Comparison

## Quick Summary (TL;DR)
- Winner for Protein: [X]
- Winner for Cost: [Y]
- Winner for Sustainability: [Z]
- Overall Best: [Winner with reasoning]

## Nutrition Comparison
[Visual comparison table + AI insights]

## Taste & Texture
[AI-generated description]

## Best Uses
**[Ingredient A]:** [When to use]
**[Ingredient B]:** [When to use]

## Cost Comparison
[Price analysis]

## Environmental Impact
[Sustainability data]

## Bottom Line
[AI-generated conclusion with recommendations]
```

### 2.3 Comparison URL Structure

**SEO-Optimized URLs:**
- `/compare/tofu-vs-tempeh`
- `/compare/almond-milk-vs-oat-milk`
- `/compare/quinoa-vs-rice`

**Auto-generate reverse:**
- `/compare/tempeh-vs-tofu` → redirects to `/compare/tofu-vs-tempeh` (alphabetical)

---

## Phase 3: Search & Discovery 🔍

### 3.1 Ingredient Search

**Features:**
- Full-text search across ingredients
- Filter by category (protein, grain, milk, etc.)
- Filter by nutrition criteria:
  - High protein (>10g per 100g)
  - Low calorie (<100 cal per 100g)
  - High fiber
  - High iron
  - etc.
- Sort by: popularity, price, protein, calories

### 3.2 Comparison Finder

**Interactive Tool:**
```
┌─────────────────────────────────────────┐
│  Compare Vegan Ingredients              │
├─────────────────────────────────────────┤
│  Select Ingredient 1: [Tofu      ▼]    │
│  Select Ingredient 2: [Tempeh    ▼]    │
│         [Compare Now]                    │
└─────────────────────────────────────────┘
```

**Smart Suggestions:**
- "People also compared: Tofu vs Seitan"
- "Similar ingredients: Tempeh, Edamame"

### 3.3 Browse by Category

**Category Pages:**
- `/ingredients/proteins`
- `/ingredients/milk-alternatives`
- `/ingredients/grains`

Each with:
- Grid of ingredients with images
- Quick stats (protein, calories, price)
- "Compare" buttons

---

## Phase 4: AI Content Generation 🤖

### 4.1 Ingredient Page Generator

**AI Prompt Template:**
```
Generate comprehensive, factual content about [INGREDIENT] for a vegan database:

1. Description (150 words, factual, beginner-friendly)
2. Taste profile
3. Common uses in cooking
4. Health benefits (cite sources)
5. Storage tips
6. Sustainability information
7. Cost-saving tips

Style: Clear, concise, factual, beginner-friendly
Avoid: Marketing language, unverified claims
Include: Specific numbers, practical advice
```

### 4.2 Comparison Article Generator

**AI Prompt Template:**
```
Compare [INGREDIENT A] vs [INGREDIENT B] for vegan beginners:

Given nutrition data:
[Ingredient A]: [nutrition facts]
[Ingredient B]: [nutrition facts]

Create:
1. Unbiased summary (100 words)
2. Key differences (5 bullet points)
3. When to choose A (50 words)
4. When to choose B (50 words)
5. Bottom line recommendation (100 words)

Style: Factual, balanced, helpful
Avoid: Bias, marketing, preferences
Focus: Data-driven insights
```

### 4.3 Batch Generation

**Admin Feature:**
- Select multiple ingredient pairs
- Queue comparison generation
- Review before publishing
- Bulk operations

---

## Phase 5: SEO Optimization 📈

### 5.1 Schema.org Markup

**Ingredient Pages:**
```json
{
  "@context": "https://schema.org",
  "@type": "NutritionInformation",
  "name": "Tofu",
  "calories": "76 calories",
  "proteinContent": "8g",
  "fatContent": "4.8g"
}
```

**Comparison Pages:**
```json
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "Tofu vs Tempeh: Complete Comparison",
  "description": "...",
  "about": [
    {"@type": "Thing", "name": "Tofu"},
    {"@type": "Thing", "name": "Tempeh"}
  ]
}
```

### 5.2 Target Keywords

**Long-tail keywords:**
- "tofu vs tempeh protein"
- "is oat milk better than almond milk"
- "vegan protein sources comparison"
- "plant based milk alternatives"

**Question keywords:**
- "what is the best vegan milk?"
- "which has more protein tofu or tempeh?"
- "is quinoa better than rice?"

### 5.3 Internal Linking

**Auto-link strategy:**
- Ingredient pages link to comparisons
- Comparison pages link to individual ingredients
- Related comparisons suggested at bottom
- "Best for X" collections

---

## Phase 6: User Experience Features 🎨

### 6.1 Comparison Calculator

**Interactive Tool:**
- Input serving size
- See nutrition for that amount
- Compare custom portions
- Add to meal planner

### 6.2 Visual Comparison Charts

**Chart Types:**
- Bar charts for nutrition
- Radar charts for overall comparison
- Cost comparison graphs
- Environmental impact visualization

### 6.3 Personalization

**User Preferences:**
- Dietary restrictions (gluten-free, nut-free)
- Budget preferences
- Favorite ingredients
- Shopping list feature

### 6.4 Mobile Experience

**Mobile-First:**
- Swipe comparisons
- Quick filters
- Simplified tables
- Fast loading (<2s)

---

## Phase 7: Data Sources & Verification 📊

### 7.1 Nutrition Data Sources

**Approved Sources:**
1. **USDA FoodData Central** (primary)
2. **NIH Nutrition Database**
3. **Manufacturer data** (verified)
4. **Scientific studies** (peer-reviewed)

### 7.2 Verification Workflow

1. AI generates content
2. Auto-populate from USDA API
3. Editor reviews for accuracy
4. Mark as "verified"
5. Display verification badge

### 7.3 Update Schedule

- Nutrition data: Review annually
- Prices: Update quarterly
- Availability: Update as needed
- Reviews: Monitor continuously

---

## Phase 8: Performance & Speed ⚡

### 8.1 Speed Targets

- **Page Load:** <2 seconds
- **Time to Interactive:** <3 seconds
- **First Contentful Paint:** <1 second

### 8.2 Optimization Strategy

1. **Database:**
   - Indexed searches
   - Query optimization
   - Redis caching

2. **Frontend:**
   - CDN for images
   - Lazy loading
   - Minified assets
   - Critical CSS inline

3. **API:**
   - Cache comparison results
   - Pre-generate popular comparisons
   - Background jobs for generation

### 8.3 Caching Strategy

**Cache Layers:**
- Database query cache (1 hour)
- Page cache (24 hours)
- CDN cache (7 days)
- API response cache (1 hour)

---

## Phase 9: Monetization Strategy 💰

### 9.1 Affiliate Links

- Link to products on Amazon, Thrive Market
- "Where to buy" sections
- Product recommendations

### 9.2 Sponsored Content

- "Featured product" slots
- Brand partnerships (clearly labeled)
- Comparison sponsorships

### 9.3 Premium Features

- Advanced meal planning
- Personalized recommendations
- Export shopping lists
- Nutritionist consultations

---

## Implementation Roadmap 📅

### Sprint 1 (Week 1-2): Database Foundation
- [ ] Create ingredient table schema
- [ ] Create comparison table schema
- [ ] Create Ingredient model
- [ ] Create Comparison model
- [ ] Seed 20 initial ingredients
- [ ] Admin CRUD for ingredients

### Sprint 2 (Week 3-4): Basic Comparison Engine
- [ ] Comparison generator controller
- [ ] Comparison view template
- [ ] AI prompt for comparisons
- [ ] Generate 10 test comparisons
- [ ] Comparison index page

### Sprint 3 (Week 5-6): Search & Discovery
- [ ] Ingredient search page
- [ ] Category browse pages
- [ ] Filter system
- [ ] Comparison finder tool
- [ ] Smart suggestions

### Sprint 4 (Week 7-8): AI Automation
- [ ] Batch ingredient generation
- [ ] Batch comparison generation
- [ ] Queue system integration
- [ ] Review workflow
- [ ] Verification system

### Sprint 5 (Week 9-10): SEO & Performance
- [ ] Schema.org for all pages
- [ ] Sitemap generation
- [ ] Performance optimization
- [ ] Image optimization
- [ ] Caching implementation

### Sprint 6 (Week 11-12): Polish & Launch
- [ ] Visual comparison charts
- [ ] Mobile optimization
- [ ] User testing
- [ ] Bug fixes
- [ ] Launch preparation

---

## Success Metrics 📊

### Traffic Targets (6 months)
- 50,000 monthly visitors
- 5,000 comparison views/day
- 100+ ingredients in database
- 500+ comparison articles

### Engagement Metrics
- Average session: >2 minutes
- Pages per session: >3
- Bounce rate: <60%
- Return visitor rate: >30%

### SEO Metrics
- 100+ keywords in top 10
- Domain authority: >30
- Backlinks: >100
- Featured snippets: 20+

---

## Competitive Advantages 🏆

### What Makes Us Different

1. **Data-Driven:** Factual, sourced information
2. **Comprehensive:** Widest ingredient database
3. **Unbiased:** No brand preferences
4. **AI-Powered:** Fresh, updated content
5. **Fast:** <2s page loads
6. **Beginner-Friendly:** Clear, simple language
7. **Verified:** Expert-reviewed content

---

## Technical Stack Summary

**Backend:**
- Yii2 framework (already in place)
- MySQL with JSON support
- Redis for caching
- Queue workers for AI generation

**Frontend:**
- Bootstrap 5 (responsive)
- Chart.js for visualizations
- Alpine.js for interactivity
- Service workers for speed

**APIs:**
- OpenAI/Claude for content generation
- USDA FoodData Central for nutrition
- Unsplash for placeholder images

**Infrastructure:**
- CDN for static assets
- Separate API server (optional)
- Background workers
- Monitoring & analytics

---

## Next Steps

1. ✅ Review this strategic plan
2. 🔄 Get approval to proceed
3. 📝 Create database migration files
4. 🏗️ Build Ingredient model & CRUD
5. 🤖 Implement comparison generator
6. 🚀 Launch MVP with 50 ingredients

---

**Created:** 2025-10-31
**Status:** Strategic Plan
**Priority:** High
