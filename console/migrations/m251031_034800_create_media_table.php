<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%media}}`.
 * This table stores all media files (images, videos, etc.)
 */
class m251031_034800_create_media_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%media}}', [
            'id' => $this->primaryKey(),
            'filename' => $this->string(255)->notNull(),
            'original_filename' => $this->string(255)->notNull(),
            'file_path' => $this->string(500)->notNull()->comment('Local file path'),
            'cdn_url' => $this->string(500)->comment('CDN URL if uploaded to CDN'),
            'file_type' => $this->string(50)->notNull()->comment('image/jpeg, video/mp4, etc.'),
            'file_size' => $this->integer()->notNull()->comment('File size in bytes'),
            'mime_type' => $this->string(100)->notNull(),

            // Image specific
            'width' => $this->integer()->comment('Image width in pixels'),
            'height' => $this->integer()->comment('Image height in pixels'),
            'alt_text' => $this->string(500)->comment('Alt text for accessibility'),
            'caption' => $this->text()->comment('Image caption'),

            // Metadata
            'title' => $this->string(255),
            'description' => $this->text(),
            'tags' => $this->json()->comment('["vegan", "protein", "tofu"]'),

            // Ownership & Organization
            'uploaded_by' => $this->integer(),
            'category' => $this->string(100)->comment('ingredient, product, article, etc.'),

            // Status
            'status' => "ENUM('active', 'archived', 'deleted') DEFAULT 'active'",
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Create indexes
        $this->createIndex('idx_media_filename', '{{%media}}', 'filename');
        $this->createIndex('idx_media_file_type', '{{%media}}', 'file_type');
        $this->createIndex('idx_media_category', '{{%media}}', 'category');
        $this->createIndex('idx_media_status', '{{%media}}', 'status');

        // Add foreign key for uploaded_by
        $this->addForeignKey(
            'fk_media_uploaded_by',
            '{{%media}}',
            'uploaded_by',
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
        $this->dropForeignKey('fk_media_uploaded_by', '{{%media}}');
        $this->dropTable('{{%media}}');
    }
}
