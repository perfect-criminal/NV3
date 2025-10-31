<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comparisons}}`.
 */
class m251031_035000_create_comparisons_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comparisons}}', [
            'id' => $this->primaryKey(),
            'ingredient_a_id' => $this->integer()->notNull(),
            'ingredient_b_id' => $this->integer()->notNull(),
            'slug' => $this->string(500)->notNull()->unique()->comment('tofu-vs-tempeh'),
            'title' => $this->string(500)->notNull()->comment('Tofu vs Tempeh: Complete Comparison'),

            // Comparison Data
            'summary' => $this->text()->comment('AI-generated summary'),
            'winner_category' => $this->string(100)->comment('protein, overall, cost'),
            'comparison_data' => $this->json()->comment('Structured comparison data'),

            // Content
            'introduction' => $this->text(),
            'conclusion' => $this->text(),
            'key_differences' => $this->json(),
            'recommendations' => $this->text(),

            // SEO
            'meta_title' => $this->string(255),
            'meta_description' => $this->string(500),
            'schema_data' => $this->json(),

            // AI & Quality
            'ai_generated' => $this->boolean()->defaultValue(0),
            'ai_model' => $this->string(100),
            'generated_at' => $this->timestamp()->null(),
            'reviewed_by' => $this->integer(),
            'reviewed_at' => $this->timestamp()->null(),

            // Stats
            'view_count' => $this->integer()->defaultValue(0),
            'helpful_count' => $this->integer()->defaultValue(0),
            'not_helpful_count' => $this->integer()->defaultValue(0),

            // Status
            'status' => "ENUM('draft', 'published', 'archived') DEFAULT 'draft'",
            'published_at' => $this->timestamp()->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Create indexes
        $this->createIndex('idx_comparisons_ingredients', '{{%comparisons}}', ['ingredient_a_id', 'ingredient_b_id']);
        $this->createIndex('idx_comparisons_status', '{{%comparisons}}', 'status');
        $this->createIndex('idx_comparisons_view_count', '{{%comparisons}}', 'view_count');

        // Add foreign keys
        $this->addForeignKey(
            'fk_comparison_ingredient_a',
            '{{%comparisons}}',
            'ingredient_a_id',
            '{{%ingredients}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_comparison_ingredient_b',
            '{{%comparisons}}',
            'ingredient_b_id',
            '{{%ingredients}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_comparison_reviewer',
            '{{%comparisons}}',
            'reviewed_by',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_comparison_ingredient_a', '{{%comparisons}}');
        $this->dropForeignKey('fk_comparison_ingredient_b', '{{%comparisons}}');
        $this->dropForeignKey('fk_comparison_reviewer', '{{%comparisons}}');
        $this->dropTable('{{%comparisons}}');
    }
}
