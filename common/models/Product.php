<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\helpers\Json;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property int|null $ingredient_id
 * @property string $name
 * @property string $slug
 * @property string $brand
 * @property string $category
 * @property string|null $description
 * @property float|null $price
 * @property string $currency
 * @property string|null $package_size
 * @property string|null $barcode
 * @property string|null $serving_size
 * @property array|null $nutrition_data
 * @property array|null $where_to_buy
 * @property array|null $availability_region
 * @property float|null $rating
 * @property int $rating_count
 * @property int|null $featured_image_id
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property array|null $schema_data
 * @property string $status
 * @property int $verified
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Ingredient $ingredient
 * @property Media $featuredImage
 */
class Product extends ActiveRecord
{
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_DISCONTINUED = 'discontinued';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'immutable' => false,
                'ensureUnique' => true,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'brand', 'category'], 'required'],
            [['ingredient_id', 'rating_count', 'verified', 'featured_image_id'], 'integer'],
            [['description'], 'string'],
            [['price', 'rating'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'in', 'range' => [self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_DISCONTINUED]],
            [['name', 'slug', 'brand', 'meta_title'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['category', 'package_size', 'barcode', 'serving_size'], 'string', 'max' => 100],
            [['currency'], 'string', 'max' => 10],
            [['meta_description'], 'string', 'max' => 500],

            // JSON fields
            [['nutrition_data', 'where_to_buy', 'availability_region', 'schema_data'], 'safe'],

            // Foreign keys
            [['ingredient_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ingredient::class, 'targetAttribute' => ['ingredient_id' => 'id']],
            [['featured_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::class, 'targetAttribute' => ['featured_image_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ingredient_id' => 'Base Ingredient',
            'name' => 'Product Name',
            'slug' => 'Slug',
            'brand' => 'Brand',
            'category' => 'Category',
            'description' => 'Description',
            'price' => 'Price',
            'currency' => 'Currency',
            'package_size' => 'Package Size',
            'barcode' => 'Barcode',
            'serving_size' => 'Serving Size',
            'nutrition_data' => 'Nutrition Data',
            'where_to_buy' => 'Where to Buy',
            'availability_region' => 'Availability Region',
            'rating' => 'Rating',
            'rating_count' => 'Rating Count',
            'featured_image_id' => 'Featured Image',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'schema_data' => 'Schema.org Data',
            'status' => 'Status',
            'verified' => 'Verified',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Ingredient]].
     */
    public function getIngredient()
    {
        return $this->hasOne(Ingredient::class, ['id' => 'ingredient_id']);
    }

    /**
     * Gets query for [[FeaturedImage]].
     */
    public function getFeaturedImage()
    {
        return $this->hasOne(Media::class, ['id' => 'featured_image_id']);
    }

    /**
     * Get all statuses
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_DISCONTINUED => 'Discontinued',
        ];
    }

    /**
     * Before save
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Convert JSON fields to JSON if they are arrays
            $jsonFields = ['nutrition_data', 'where_to_buy', 'availability_region', 'schema_data'];

            foreach ($jsonFields as $field) {
                if (is_array($this->$field)) {
                    $this->$field = Json::encode($this->$field);
                }
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

        // Decode JSON fields
        $jsonFields = ['nutrition_data', 'where_to_buy', 'availability_region', 'schema_data'];

        foreach ($jsonFields as $field) {
            if (is_string($this->$field) && !empty($this->$field)) {
                $this->$field = Json::decode($this->$field);
            }
        }
    }

    /**
     * Get product URL
     */
    public function getUrl()
    {
        return '/product/' . $this->slug;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPrice()
    {
        return $this->currency . ' ' . number_format($this->price, 2);
    }
}
