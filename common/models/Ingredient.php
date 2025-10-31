<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;
use yii\helpers\Json;

/**
 * This is the model class for table "ingredients".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $category
 * @property string|null $subcategory
 * @property array|null $common_names
 * @property string|null $scientific_name
 * @property string|null $origin
 * @property string|null $description
 * @property string|null $summary
 * @property string|null $taste_profile
 * @property string|null $texture
 * @property float|null $calories
 * @property float|null $protein
 * @property float|null $fat
 * @property float|null $carbs
 * @property float|null $fiber
 * @property float|null $sugar
 * @property float|null $sodium
 * @property float|null $vitamin_b12
 * @property float|null $vitamin_d
 * @property float|null $iron
 * @property float|null $calcium
 * @property float|null $zinc
 * @property float|null $omega3
 * @property array|null $nutrition_extended
 * @property array|null $health_benefits
 * @property array|null $allergens
 * @property array|null $dietary_flags
 * @property array|null $cooking_methods
 * @property array|null $common_uses
 * @property array|null $substitutes
 * @property string|null $storage_tips
 * @property string|null $preparation_tips
 * @property string|null $season
 * @property int|null $sustainability_score
 * @property string|null $environmental_impact
 * @property array|null $certifications
 * @property float|null $avg_price_per_kg
 * @property string $availability
 * @property int|null $featured_image_id
 * @property array|null $gallery_images
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property array|null $schema_data
 * @property int $ai_generated
 * @property int $data_verified
 * @property int|null $verified_by
 * @property string|null $verified_at
 * @property array|null $sources
 * @property int $view_count
 * @property int $comparison_count
 * @property float|null $rating
 * @property int $rating_count
 * @property string $status
 * @property string|null $published_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Media $featuredImage
 * @property User $verifiedBy
 * @property Comparison[] $comparisonsA
 * @property Comparison[] $comparisonsB
 * @property Product[] $products
 */
class Ingredient extends ActiveRecord
{
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED = 'archived';

    const CATEGORY_PROTEIN = 'protein';
    const CATEGORY_GRAIN = 'grain';
    const CATEGORY_VEGETABLE = 'vegetable';
    const CATEGORY_FRUIT = 'fruit';
    const CATEGORY_NUT = 'nut';
    const CATEGORY_SEED = 'seed';
    const CATEGORY_LEGUME = 'legume';
    const CATEGORY_MILK_ALTERNATIVE = 'milk_alternative';
    const CATEGORY_OTHER = 'other';

    const AVAILABILITY_COMMON = 'common';
    const AVAILABILITY_MODERATE = 'moderate';
    const AVAILABILITY_RARE = 'rare';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ingredients';
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
            [['name', 'category'], 'required'],
            [['category'], 'in', 'range' => [
                self::CATEGORY_PROTEIN,
                self::CATEGORY_GRAIN,
                self::CATEGORY_VEGETABLE,
                self::CATEGORY_FRUIT,
                self::CATEGORY_NUT,
                self::CATEGORY_SEED,
                self::CATEGORY_LEGUME,
                self::CATEGORY_MILK_ALTERNATIVE,
                self::CATEGORY_OTHER,
            ]],
            [['status'], 'in', 'range' => [self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_ARCHIVED]],
            [['availability'], 'in', 'range' => [self::AVAILABILITY_COMMON, self::AVAILABILITY_MODERATE, self::AVAILABILITY_RARE]],
            [['description', 'storage_tips', 'preparation_tips', 'environmental_impact'], 'string'],
            [['calories', 'protein', 'fat', 'carbs', 'fiber', 'sugar', 'sodium', 'vitamin_b12', 'vitamin_d', 'iron', 'calcium', 'zinc', 'omega3', 'avg_price_per_kg', 'rating'], 'number'],
            [['sustainability_score', 'view_count', 'comparison_count', 'rating_count', 'ai_generated', 'data_verified', 'verified_by', 'featured_image_id'], 'integer'],
            [['verified_at', 'published_at', 'created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'scientific_name', 'origin', 'taste_profile', 'texture', 'season', 'meta_title'], 'string', 'max' => 255],
            [['subcategory'], 'string', 'max' => 100],
            [['summary', 'meta_description', 'meta_keywords'], 'string', 'max' => 500],
            [['slug'], 'unique'],

            // JSON fields
            [['common_names', 'nutrition_extended', 'health_benefits', 'allergens', 'dietary_flags',
              'cooking_methods', 'common_uses', 'substitutes', 'certifications', 'gallery_images',
              'schema_data', 'sources'], 'safe'],

            // Foreign keys
            [['verified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['verified_by' => 'id']],
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
            'name' => 'Name',
            'slug' => 'Slug',
            'category' => 'Category',
            'subcategory' => 'Subcategory',
            'common_names' => 'Common Names',
            'scientific_name' => 'Scientific Name',
            'origin' => 'Origin',
            'description' => 'Description',
            'summary' => 'Summary',
            'taste_profile' => 'Taste Profile',
            'texture' => 'Texture',
            'calories' => 'Calories (per 100g)',
            'protein' => 'Protein (g)',
            'fat' => 'Fat (g)',
            'carbs' => 'Carbohydrates (g)',
            'fiber' => 'Fiber (g)',
            'sugar' => 'Sugar (g)',
            'sodium' => 'Sodium (mg)',
            'vitamin_b12' => 'Vitamin B12 (% DV)',
            'vitamin_d' => 'Vitamin D (% DV)',
            'iron' => 'Iron (% DV)',
            'calcium' => 'Calcium (% DV)',
            'zinc' => 'Zinc (% DV)',
            'omega3' => 'Omega-3 (g)',
            'nutrition_extended' => 'Extended Nutrition Data',
            'health_benefits' => 'Health Benefits',
            'allergens' => 'Allergens',
            'dietary_flags' => 'Dietary Flags',
            'cooking_methods' => 'Cooking Methods',
            'common_uses' => 'Common Uses',
            'substitutes' => 'Substitutes',
            'storage_tips' => 'Storage Tips',
            'preparation_tips' => 'Preparation Tips',
            'season' => 'Season',
            'sustainability_score' => 'Sustainability Score',
            'environmental_impact' => 'Environmental Impact',
            'certifications' => 'Certifications',
            'avg_price_per_kg' => 'Average Price per KG',
            'availability' => 'Availability',
            'featured_image_id' => 'Featured Image',
            'gallery_images' => 'Gallery Images',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'schema_data' => 'Schema.org Data',
            'ai_generated' => 'AI Generated',
            'data_verified' => 'Data Verified',
            'verified_by' => 'Verified By',
            'verified_at' => 'Verified At',
            'sources' => 'Data Sources',
            'view_count' => 'View Count',
            'comparison_count' => 'Comparison Count',
            'rating' => 'Rating',
            'rating_count' => 'Rating Count',
            'status' => 'Status',
            'published_at' => 'Published At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[FeaturedImage]].
     */
    public function getFeaturedImage()
    {
        return $this->hasOne(Media::class, ['id' => 'featured_image_id']);
    }

    /**
     * Gets query for [[VerifiedBy]].
     */
    public function getVerifiedBy()
    {
        return $this->hasOne(User::class, ['id' => 'verified_by']);
    }

    /**
     * Gets query for [[ComparisonsA]].
     */
    public function getComparisonsA()
    {
        return $this->hasMany(Comparison::class, ['ingredient_a_id' => 'id']);
    }

    /**
     * Gets query for [[ComparisonsB]].
     */
    public function getComparisonsB()
    {
        return $this->hasMany(Comparison::class, ['ingredient_b_id' => 'id']);
    }

    /**
     * Gets query for [[Products]].
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['ingredient_id' => 'id']);
    }

    /**
     * Get all categories
     */
    public static function getCategories()
    {
        return [
            self::CATEGORY_PROTEIN => 'Protein',
            self::CATEGORY_GRAIN => 'Grain',
            self::CATEGORY_VEGETABLE => 'Vegetable',
            self::CATEGORY_FRUIT => 'Fruit',
            self::CATEGORY_NUT => 'Nut',
            self::CATEGORY_SEED => 'Seed',
            self::CATEGORY_LEGUME => 'Legume',
            self::CATEGORY_MILK_ALTERNATIVE => 'Milk Alternative',
            self::CATEGORY_OTHER => 'Other',
        ];
    }

    /**
     * Get all statuses
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_ARCHIVED => 'Archived',
        ];
    }

    /**
     * Get all availability options
     */
    public static function getAvailabilityOptions()
    {
        return [
            self::AVAILABILITY_COMMON => 'Common',
            self::AVAILABILITY_MODERATE => 'Moderate',
            self::AVAILABILITY_RARE => 'Rare',
        ];
    }

    /**
     * Before save
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Convert JSON fields to JSON if they are arrays
            $jsonFields = ['common_names', 'nutrition_extended', 'health_benefits', 'allergens',
                          'dietary_flags', 'cooking_methods', 'common_uses', 'substitutes',
                          'certifications', 'gallery_images', 'schema_data', 'sources'];

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
        $jsonFields = ['common_names', 'nutrition_extended', 'health_benefits', 'allergens',
                      'dietary_flags', 'cooking_methods', 'common_uses', 'substitutes',
                      'certifications', 'gallery_images', 'schema_data', 'sources'];

        foreach ($jsonFields as $field) {
            if (is_string($this->$field) && !empty($this->$field)) {
                $this->$field = Json::decode($this->$field);
            }
        }
    }
}
