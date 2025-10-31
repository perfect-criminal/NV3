<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products}}`.
 */
class m251031_035100_create_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'ingredient_id' => $this->integer()->comment('Link to base ingredient'),
            'name' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull()->unique(),
            'brand' => $this->string(255)->notNull(),
            'category' => $this->string(100)->notNull(),

            // Product Info
            'description' => $this->text(),
            'price' => $this->decimal(10, 2),
            'currency' => $this->string(10)->defaultValue('USD'),
            'package_size' => $this->string(100)->comment('500g, 1L'),
            'barcode' => $this->string(100),

            // Nutrition (per serving)
            'serving_size' => $this->string(100),
            'nutrition_data' => $this->json(),

            // Availability
            'where_to_buy' => $this->json()->comment('[{"store": "Whole Foods", "url": "..."}]'),
            'availability_region' => $this->json()->comment('["US", "EU", "Asia"]'),

            // Ratings & Reviews
            'rating' => $this->decimal(3, 2),
            'rating_count' => $this->integer()->defaultValue(0),

            // Media & SEO
            'featured_image_id' => $this->integer(),
            'meta_title' => $this->string(255),
            'meta_description' => $this->string(500),
            'schema_data' => $this->json(),

            // Status
            'status' => "ENUM('draft', 'published', 'discontinued') DEFAULT 'draft'",
            'verified' => $this->boolean()->defaultValue(0),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Create indexes
        $this->createIndex('idx_products_ingredient', '{{%products}}', 'ingredient_id');
        $this->createIndex('idx_products_brand', '{{%products}}', 'brand');
        $this->createIndex('idx_products_category', '{{%products}}', 'category');

        // Add foreign key for ingredient
        $this->addForeignKey(
            'fk_product_ingredient',
            '{{%products}}',
            'ingredient_id',
            '{{%ingredients}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        // Foreign key for featured_image_id will be added after media table is created
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_product_ingredient', '{{%products}}');
        $this->dropTable('{{%products}}');
    }
}
