<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Ingredient;

/**
 * IngredientSearch represents the model behind the search form of `common\models\Ingredient`.
 */
class IngredientSearch extends Ingredient
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'featured_image_id', 'verified_by', 'view_count', 'comparison_count', 'rating_count', 'sustainability_score'], 'integer'],
            [['name', 'slug', 'category', 'subcategory', 'scientific_name', 'origin', 'status', 'availability'], 'safe'],
            [['calories', 'protein', 'fat', 'carbs', 'fiber', 'rating'], 'number'],
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
        $query = Ingredient::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'category' => $this->category,
            'status' => $this->status,
            'availability' => $this->availability,
            'calories' => $this->calories,
            'protein' => $this->protein,
            'fat' => $this->fat,
            'carbs' => $this->carbs,
            'fiber' => $this->fiber,
            'sustainability_score' => $this->sustainability_score,
            'featured_image_id' => $this->featured_image_id,
            'verified_by' => $this->verified_by,
            'view_count' => $this->view_count,
            'comparison_count' => $this->comparison_count,
            'rating' => $this->rating,
            'rating_count' => $this->rating_count,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'subcategory', $this->subcategory])
            ->andFilterWhere(['like', 'scientific_name', $this->scientific_name])
            ->andFilterWhere(['like', 'origin', $this->origin]);

        return $dataProvider;
    }
}
