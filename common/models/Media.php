<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * This is the model class for table "media".
 *
 * @property int $id
 * @property string $filename
 * @property string $original_filename
 * @property string $file_path
 * @property string|null $cdn_url
 * @property string $file_type
 * @property int $file_size
 * @property string $mime_type
 * @property int|null $width
 * @property int|null $height
 * @property string|null $alt_text
 * @property string|null $caption
 * @property string|null $title
 * @property string|null $description
 * @property array|null $tags
 * @property int|null $uploaded_by
 * @property string|null $category
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $uploadedBy
 */
class Media extends ActiveRecord
{
    const STATUS_ACTIVE = 'active';
    const STATUS_ARCHIVED = 'archived';
    const STATUS_DELETED = 'deleted';

    /**
     * @var UploadedFile
     */
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['filename', 'original_filename', 'file_path', 'file_type', 'file_size', 'mime_type'], 'required'],
            [['file_size', 'width', 'height', 'uploaded_by'], 'integer'],
            [['description', 'caption'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_ARCHIVED, self::STATUS_DELETED]],
            [['filename', 'original_filename', 'title'], 'string', 'max' => 255],
            [['file_path', 'cdn_url', 'alt_text'], 'string', 'max' => 500],
            [['file_type', 'mime_type'], 'string', 'max' => 100],
            [['category'], 'string', 'max' => 100],

            // JSON fields
            [['tags'], 'safe'],

            // Foreign keys
            [['uploaded_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['uploaded_by' => 'id']],

            // File upload
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif, webp'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Filename',
            'original_filename' => 'Original Filename',
            'file_path' => 'File Path',
            'cdn_url' => 'CDN URL',
            'file_type' => 'File Type',
            'file_size' => 'File Size',
            'mime_type' => 'MIME Type',
            'width' => 'Width',
            'height' => 'Height',
            'alt_text' => 'Alt Text',
            'caption' => 'Caption',
            'title' => 'Title',
            'description' => 'Description',
            'tags' => 'Tags',
            'uploaded_by' => 'Uploaded By',
            'category' => 'Category',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[UploadedBy]].
     */
    public function getUploadedBy()
    {
        return $this->hasOne(User::class, ['id' => 'uploaded_by']);
    }

    /**
     * Get all statuses
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_ARCHIVED => 'Archived',
            self::STATUS_DELETED => 'Deleted',
        ];
    }

    /**
     * Before save
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Convert tags to JSON if they are arrays
            if (is_array($this->tags)) {
                $this->tags = Json::encode($this->tags);
            }

            return true;
        }
        return false;
    }

    /**
     * After find
     */
    public function afterFind()
    {
        parent::afterFind();

        // Decode tags
        if (is_string($this->tags) && !empty($this->tags)) {
            $this->tags = Json::decode($this->tags);
        }
    }

    /**
     * Get full URL
     */
    public function getUrl()
    {
        // Return CDN URL if available, otherwise return local path
        return $this->cdn_url ?: Yii::getAlias('@web') . '/' . $this->file_path;
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSize()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if file is image
     */
    public function isImage()
    {
        return strpos($this->file_type, 'image/') === 0;
    }

    /**
     * Check if file is video
     */
    public function isVideo()
    {
        return strpos($this->file_type, 'video/') === 0;
    }

    /**
     * Upload file
     */
    public function upload()
    {
        if ($this->validate()) {
            $uploadPath = Yii::getAlias('@frontend/web/uploads/');

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $filename = Yii::$app->security->generateRandomString() . '.' . $this->imageFile->extension;
            $filePath = $uploadPath . $filename;

            if ($this->imageFile->saveAs($filePath)) {
                $this->filename = $filename;
                $this->original_filename = $this->imageFile->baseName . '.' . $this->imageFile->extension;
                $this->file_path = 'uploads/' . $filename;
                $this->file_type = $this->imageFile->type;
                $this->file_size = $this->imageFile->size;
                $this->mime_type = $this->imageFile->type;

                // Get image dimensions if it's an image
                if ($this->isImage()) {
                    list($width, $height) = getimagesize($filePath);
                    $this->width = $width;
                    $this->height = $height;
                }

                $this->uploaded_by = Yii::$app->user->id;
                $this->status = self::STATUS_ACTIVE;

                return $this->save();
            }
        }
        return false;
    }
}
