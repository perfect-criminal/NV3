<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Comparison;

/**
 * ComparisonSearch represents the model behind the search form of `common\models\Comparison`.
 */
class ComparisonSearch extends Comparison
{
    public $ingredient_a_name;
    public $ingredient_b_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ingredient_a_id', 'ingredient_b_id', 'reviewed_by', 'view_count', 'helpful_count', 'not_helpful_count', 'ai_generated'], 'integer'],
            [['slug', 'title', 'status', 'ai_model', 'winner_category', 'ingredient_a_name', 'ingredient_b_name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Comparison::find()
            ->joinWith(['ingredientA', 'ingredientB']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
                'attributes' => [
                    'id',
                    'title',
                    'status',
                    'view_count',
                    'helpful_count',
                    'ai_generated',
                    'created_at',
                    'ingredient_a_name' => [
                        'asc' => ['ingredients.name' => SORT_ASC],
                        'desc' => ['ingredients.name' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'comparisons.id' => $this->id,
            'ingredient_a_id' => $this->ingredient_a_id,
            'ingredient_b_id' => $this->ingredient_b_id,
            'comparisons.status' => $this->status,
            'ai_generated' => $this->ai_generated,
            'reviewed_by' => $this->reviewed_by,
            'view_count' => $this->view_count,
            'helpful_count' => $this->helpful_count,
            'not_helpful_count' => $this->not_helpful_count,
        ]);

        $query->andFilterWhere(['like', 'comparisons.slug', $this->slug])
            ->andFilterWhere(['like', 'comparisons.title', $this->title])
            ->andFilterWhere(['like', 'ai_model', $this->ai_model])
            ->andFilterWhere(['like', 'winner_category', $this->winner_category]);

        return $dataProvider;
    }
}
