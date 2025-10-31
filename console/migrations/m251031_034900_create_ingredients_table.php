<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ingredients}}`.
 */
class m251031_034900_create_ingredients_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ingredients}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull()->unique(),
            'category' => "ENUM('protein', 'grain', 'vegetable', 'fruit', 'nut', 'seed', 'legume', 'milk_alternative', 'other') NOT NULL",
            'subcategory' => $this->string(100),
            'common_names' => $this->json()->comment('["tofu", "bean curd", "doufu"]'),
            'scientific_name' => $this->string(255),
            'origin' => $this->string(255),

            // Basic Info
            'description' => $this->text(),
            'summary' => $this->string(500),
            'taste_profile' => $this->string(255)->comment('Mild, slightly nutty'),
            'texture' => $this->string(255)->comment('Firm, soft, silky'),

            // Nutrition per 100g (standardized)
            'calories' => $this->decimal(8, 2),
            'protein' => $this->decimal(8, 2),
            'fat' => $this->decimal(8, 2),
            'carbs' => $this->decimal(8, 2),
            'fiber' => $this->decimal(8, 2),
            'sugar' => $this->decimal(8, 2),
            'sodium' => $this->decimal(8, 2),

            // Vitamins & Minerals (% daily value)
            'vitamin_b12' => $this->decimal(8, 2),
            'vitamin_d' => $this->decimal(8, 2),
            'iron' => $this->decimal(8, 2),
            'calcium' => $this->decimal(8, 2),
            'zinc' => $this->decimal(8, 2),
            'omega3' => $this->decimal(8, 2),

            // Additional nutrition data (JSON for flexibility)
            'nutrition_extended' => $this->json(),

            // Health & Benefits
            'health_benefits' => $this->json()->comment('["High protein", "Rich in iron"]'),
            'allergens' => $this->json()->comment('["soy", "gluten"]'),
            'dietary_flags' => $this->json()->comment('["gluten-free", "soy-free", "nut-free"]'),

            // Usage & Cooking
            'cooking_methods' => $this->json()->comment('["grilled", "baked", "raw"]'),
            'common_uses' => $this->json()->comment('["salads", "stir-fry", "smoothies"]'),
            'substitutes' => $this->json()->comment('[{"id": 5, "name": "tempeh", "ratio": "1:1"}]'),
            'storage_tips' => $this->text(),
            'preparation_tips' => $this->text(),

            // Sourcing & Sustainability
            'season' => $this->string(255),
            'sustainability_score' => $this->tinyInteger()->comment('1-10'),
            'environmental_impact' => $this->text(),
            'certifications' => $this->json()->comment('["organic", "fair-trade"]'),

            // Cost & Availability
            'avg_price_per_kg' => $this->decimal(8, 2),
            'availability' => "ENUM('common', 'moderate', 'rare') DEFAULT 'common'",

            // Media & SEO
            'featured_image_id' => $this->integer(),
            'gallery_images' => $this->json()->comment('[image_id_1, image_id_2]'),
            'meta_title' => $this->string(255),
            'meta_description' => $this->string(500),
            'meta_keywords' => $this->string(500),
            'schema_data' => $this->json(),

            // AI & Data Quality
            'ai_generated' => $this->boolean()->defaultValue(0),
            'data_verified' => $this->boolean()->defaultValue(0),
            'verified_by' => $this->integer(),
            'verified_at' => $this->timestamp()->null(),
            'sources' => $this->json()->comment('["USDA", "NIH", "study_link"]'),

            // Stats & Engagement
            'view_count' => $this->integer()->defaultValue(0),
            'comparison_count' => $this->integer()->defaultValue(0)->comment('How many times used in comparisons'),
            'rating' => $this->decimal(3, 2),
            'rating_count' => $this->integer()->defaultValue(0),

            // Status & Timestamps
            'status' => "ENUM('draft', 'published', 'archived') DEFAULT 'draft'",
            'published_at' => $this->timestamp()->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Create indexes
        $this->createIndex('idx_ingredients_category', '{{%ingredients}}', 'category');
        $this->createIndex('idx_ingredients_status', '{{%ingredients}}', 'status');
        $this->createIndex('idx_ingredients_name', '{{%ingredients}}', 'name');

        // Create fulltext index for search
        $this->execute('ALTER TABLE {{%ingredients}} ADD FULLTEXT INDEX ft_search (name, description, summary)');

        // Add foreign key for verified_by
        $this->addForeignKey(
            'fk_ingredient_verified_by',
            '{{%ingredients}}',
            'verified_by',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        // Add foreign key for featured_image_id (will need media table)
        // This will be added after media table is created or if it already exists
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_ingredient_verified_by', '{{%ingredients}}');
        $this->dropTable('{{%ingredients}}');
    }
}
