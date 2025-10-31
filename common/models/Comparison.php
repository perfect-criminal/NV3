<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\helpers\Json;

/**
 * This is the model class for table "comparisons".
 *
 * @property int $id
 * @property int $ingredient_a_id
 * @property int $ingredient_b_id
 * @property string $slug
 * @property string $title
 * @property string|null $summary
 * @property string|null $winner_category
 * @property array|null $comparison_data
 * @property string|null $introduction
 * @property string|null $conclusion
 * @property array|null $key_differences
 * @property string|null $recommendations
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property array|null $schema_data
 * @property int $ai_generated
 * @property string|null $ai_model
 * @property string|null $generated_at
 * @property int|null $reviewed_by
 * @property string|null $reviewed_at
 * @property int $view_count
 * @property int $helpful_count
 * @property int $not_helpful_count
 * @property string $status
 * @property string|null $published_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Ingredient $ingredientA
 * @property Ingredient $ingredientB
 * @property User $reviewer
 */
class Comparison extends ActiveRecord
{
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED = 'archived';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comparisons';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
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
            [['ingredient_a_id', 'ingredient_b_id', 'title'], 'required'],
            [['ingredient_a_id', 'ingredient_b_id', 'ai_generated', 'reviewed_by', 'view_count', 'helpful_count', 'not_helpful_count'], 'integer'],
            [['summary', 'introduction', 'conclusion', 'recommendations'], 'string'],
            [['generated_at', 'reviewed_at', 'published_at', 'created_at', 'updated_at'], 'safe'],
            [['status'], 'in', 'range' => [self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_ARCHIVED]],
            [['slug'], 'string', 'max' => 500],
            [['slug'], 'unique'],
            [['title'], 'string', 'max' => 500],
            [['winner_category', 'ai_model'], 'string', 'max' => 100],
            [['meta_title'], 'string', 'max' => 255],
            [['meta_description'], 'string', 'max' => 500],

            // JSON fields
            [['comparison_data', 'key_differences', 'schema_data'], 'safe'],

            // Foreign keys
            [['ingredient_a_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ingredient::class, 'targetAttribute' => ['ingredient_a_id' => 'id']],
            [['ingredient_b_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ingredient::class, 'targetAttribute' => ['ingredient_b_id' => 'id']],
            [['reviewed_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['reviewed_by' => 'id']],

            // Custom validation
            ['ingredient_b_id', 'compare', 'compareAttribute' => 'ingredient_a_id', 'operator' => '!=', 'message' => 'Both ingredients must be different.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ingredient_a_id' => 'Ingredient A',
            'ingredient_b_id' => 'Ingredient B',
            'slug' => 'Slug',
            'title' => 'Title',
            'summary' => 'Summary',
            'winner_category' => 'Winner Category',
            'comparison_data' => 'Comparison Data',
            'introduction' => 'Introduction',
            'conclusion' => 'Conclusion',
            'key_differences' => 'Key Differences',
            'recommendations' => 'Recommendations',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'schema_data' => 'Schema.org Data',
            'ai_generated' => 'AI Generated',
            'ai_model' => 'AI Model',
            'generated_at' => 'Generated At',
            'reviewed_by' => 'Reviewed By',
            'reviewed_at' => 'Reviewed At',
            'view_count' => 'View Count',
            'helpful_count' => 'Helpful Count',
            'not_helpful_count' => 'Not Helpful Count',
            'status' => 'Status',
            'published_at' => 'Published At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[IngredientA]].
     */
    public function getIngredientA()
    {
        return $this->hasOne(Ingredient::class, ['id' => 'ingredient_a_id']);
    }

    /**
     * Gets query for [[IngredientB]].
     */
    public function getIngredientB()
    {
        return $this->hasOne(Ingredient::class, ['id' => 'ingredient_b_id']);
    }

    /**
     * Gets query for [[Reviewer]].
     */
    public function getReviewer()
    {
        return $this->hasOne(User::class, ['id' => 'reviewed_by']);
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
     * Before save
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Convert JSON fields to JSON if they are arrays
            $jsonFields = ['comparison_data', 'key_differences', 'schema_data'];

            foreach ($jsonFields as $field) {
                if (is_array($this->$field)) {
                    $this->$field = Json::encode($this->$field);
                }
            }

            // Auto-generate slug from ingredient names if not set
            if ($insert && empty($this->slug)) {
                $ingredientA = Ingredient::findOne($this->ingredient_a_id);
                $ingredientB = Ingredient::findOne($this->ingredient_b_id);

                if ($ingredientA && $ingredientB) {
                    $this->slug = $ingredientA->slug . '-vs-' . $ingredientB->slug;
                }
            }

            // Auto-generate title from ingredient names if not set
            if ($insert && empty($this->title)) {
                $ingredientA = Ingredient::findOne($this->ingredient_a_id);
                $ingredientB = Ingredient::findOne($this->ingredient_b_id);

                if ($ingredientA && $ingredientB) {
                    $this->title = $ingredientA->name . ' vs ' . $ingredientB->name . ': Complete Comparison';
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
        $jsonFields = ['comparison_data', 'key_differences', 'schema_data'];

        foreach ($jsonFields as $field) {
            if (is_string($this->$field) && !empty($this->$field)) {
                $this->$field = Json::decode($this->$field);
            }
        }
    }

    /**
     * Get comparison URL
     */
    public function getUrl()
    {
        return '/compare/' . $this->slug;
    }

    /**
     * Check if comparison is helpful
     */
    public function getHelpfulPercentage()
    {
        $total = $this->helpful_count + $this->not_helpful_count;
        return $total > 0 ? round(($this->helpful_count / $total) * 100) : 0;
    }
}
