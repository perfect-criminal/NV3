<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Ingredient;

/**
 * IngredientSearch represents the model behind the search form for frontend ingredient browsing
 */
class IngredientSearch extends Ingredient
{
    // Additional search attributes
    public $minProtein;
    public $maxProtein;
    public $minCalories;
    public $maxCalories;
    public $minFiber;
    public $minIron;
    public $minCalcium;
    public $highProtein; // Boolean filter
    public $lowCalorie; // Boolean filter
    public $highFiber; // Boolean filter
    public $glutenFree; // Boolean filter
    public $soyFree; // Boolean filter
    public $nutFree; // Boolean filter

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'category', 'subcategory', 'availability'], 'safe'],
            [['minProtein', 'maxProtein', 'minCalories', 'maxCalories', 'minFiber', 'minIron', 'minCalcium'], 'number'],
            [['highProtein', 'lowCalorie', 'highFiber', 'glutenFree', 'soyFree', 'nutFree'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Basic search for index page
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Ingredient::find()->where(['status' => Ingredient::STATUS_PUBLISHED]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
            'sort' => [
                'defaultOrder' => [
                    'view_count' => SORT_DESC,
                ],
                'attributes' => [
                    'name',
                    'protein',
                    'calories',
                    'view_count',
                    'rating',
                    'created_at',
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Grid filtering conditions
        $query->andFilterWhere(['category' => $this->category])
            ->andFilterWhere(['availability' => $this->availability])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'subcategory', $this->subcategory]);

        return $dataProvider;
    }

    /**
     * Advanced search with nutrition filters
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function advancedSearch($params)
    {
        $query = Ingredient::find()->where(['status' => Ingredient::STATUS_PUBLISHED]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
            'sort' => [
                'defaultOrder' => [
                    'protein' => SORT_DESC,
                ],
                'attributes' => [
                    'name',
                    'protein',
                    'calories',
                    'fiber',
                    'iron',
                    'calcium',
                    'view_count',
                    'rating',
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Basic filters
        $query->andFilterWhere(['category' => $this->category])
            ->andFilterWhere(['availability' => $this->availability])
            ->andFilterWhere(['like', 'name', $this->name]);

        // Nutrition range filters
        if ($this->minProtein !== null) {
            $query->andWhere(['>=', 'protein', $this->minProtein]);
        }
        if ($this->maxProtein !== null) {
            $query->andWhere(['<=', 'protein', $this->maxProtein]);
        }
        if ($this->minCalories !== null) {
            $query->andWhere(['>=', 'calories', $this->minCalories]);
        }
        if ($this->maxCalories !== null) {
            $query->andWhere(['<=', 'calories', $this->maxCalories]);
        }
        if ($this->minFiber !== null) {
            $query->andWhere(['>=', 'fiber', $this->minFiber]);
        }
        if ($this->minIron !== null) {
            $query->andWhere(['>=', 'iron', $this->minIron]);
        }
        if ($this->minCalcium !== null) {
            $query->andWhere(['>=', 'calcium', $this->minCalcium]);
        }

        // Boolean quick filters
        if ($this->highProtein) {
            $query->andWhere(['>=', 'protein', 10]); // >10g per 100g
        }
        if ($this->lowCalorie) {
            $query->andWhere(['<=', 'calories', 100]); // <100 cal per 100g
        }
        if ($this->highFiber) {
            $query->andWhere(['>=', 'fiber', 5]); // >5g per 100g
        }

        // Dietary flags filters (JSON field)
        if ($this->glutenFree) {
            $query->andWhere(['like', 'dietary_flags', 'gluten-free']);
        }
        if ($this->soyFree) {
            $query->andWhere(['like', 'dietary_flags', 'soy-free']);
        }
        if ($this->nutFree) {
            $query->andWhere(['like', 'dietary_flags', 'nut-free']);
        }

        return $dataProvider;
    }

    /**
     * Get filter labels
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'minProtein' => 'Min Protein (g)',
            'maxProtein' => 'Max Protein (g)',
            'minCalories' => 'Min Calories',
            'maxCalories' => 'Max Calories',
            'minFiber' => 'Min Fiber (g)',
            'minIron' => 'Min Iron (% DV)',
            'minCalcium' => 'Min Calcium (% DV)',
            'highProtein' => 'High Protein (>10g)',
            'lowCalorie' => 'Low Calorie (<100)',
            'highFiber' => 'High Fiber (>5g)',
            'glutenFree' => 'Gluten-Free',
            'soyFree' => 'Soy-Free',
            'nutFree' => 'Nut-Free',
        ]);
    }
}
