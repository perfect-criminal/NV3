<?php

use yii\db\Migration;

/**
 * Adds foreign keys for media relations to ingredients and products tables.
 */
class m251031_035200_add_media_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Add foreign key for ingredients table
        $this->addForeignKey(
            'fk_ingredient_image',
            '{{%ingredients}}',
            'featured_image_id',
            '{{%media}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        // Add foreign key for products table
        $this->addForeignKey(
            'fk_product_image',
            '{{%products}}',
            'featured_image_id',
            '{{%media}}',
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
        $this->dropForeignKey('fk_ingredient_image', '{{%ingredients}}');
        $this->dropForeignKey('fk_product_image', '{{%products}}');
    }
}
