<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\Ingredient;
use frontend\models\IngredientSearch;
use yii\data\ActiveDataProvider;

/**
 * IngredientController handles ingredient browsing and search for frontend
 */
class IngredientController extends Controller
{
    /**
     * Displays all published ingredients with search and filters
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new IngredientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Browse ingredients by category
     * @param string $category
     * @return string
     */
    public function actionCategory($category)
    {
        // Validate category
        $categories = Ingredient::getCategories();
        if (!isset($categories[$category])) {
            throw new NotFoundHttpException('Category not found.');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Ingredient::find()
                ->where(['category' => $category, 'status' => Ingredient::STATUS_PUBLISHED])
                ->orderBy(['view_count' => SORT_DESC, 'name' => SORT_ASC]),
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        return $this->render('category', [
            'category' => $category,
            'categoryName' => $categories[$category],
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Display single ingredient details
     * @param string $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($slug)
    {
        $model = $this->findModelBySlug($slug);

        // Increment view count
        $model->updateCounters(['view_count' => 1]);

        // Get related comparisons
        $relatedComparisons = $model->getComparisonsA()
            ->orWhere(['ingredient_b_id' => $model->id])
            ->andWhere(['status' => 'published'])
            ->limit(5)
            ->all();

        // Get similar ingredients (same category)
        $similarIngredients = Ingredient::find()
            ->where(['category' => $model->category, 'status' => Ingredient::STATUS_PUBLISHED])
            ->andWhere(['!=', 'id', $model->id])
            ->orderBy(['view_count' => SORT_DESC])
            ->limit(4)
            ->all();

        // Get possible substitutes
        $substitutes = [];
        if (is_array($model->substitutes) && !empty($model->substitutes)) {
            foreach ($model->substitutes as $sub) {
                if (isset($sub['id'])) {
                    $ingredient = Ingredient::findOne($sub['id']);
                    if ($ingredient && $ingredient->status === Ingredient::STATUS_PUBLISHED) {
                        $substitutes[] = $ingredient;
                    }
                }
            }
        }

        return $this->render('view', [
            'model' => $model,
            'relatedComparisons' => $relatedComparisons,
            'similarIngredients' => $similarIngredients,
            'substitutes' => $substitutes,
        ]);
    }

    /**
     * Comparison finder tool
     * @return string
     */
    public function actionFinder()
    {
        $ingredientA = null;
        $ingredientB = null;
        $comparison = null;

        if (Yii::$app->request->isGet) {
            $ingredientAId = Yii::$app->request->get('ingredient_a');
            $ingredientBId = Yii::$app->request->get('ingredient_b');

            if ($ingredientAId && $ingredientBId) {
                $ingredientA = Ingredient::findOne(['id' => $ingredientAId, 'status' => Ingredient::STATUS_PUBLISHED]);
                $ingredientB = Ingredient::findOne(['id' => $ingredientBId, 'status' => Ingredient::STATUS_PUBLISHED]);

                if ($ingredientA && $ingredientB) {
                    // Try to find existing comparison
                    $comparison = \common\models\Comparison::find()
                        ->where([
                            'or',
                            [
                                'ingredient_a_id' => $ingredientAId,
                                'ingredient_b_id' => $ingredientBId,
                            ],
                            [
                                'ingredient_a_id' => $ingredientBId,
                                'ingredient_b_id' => $ingredientAId,
                            ],
                        ])
                        ->andWhere(['status' => 'published'])
                        ->one();
                }
            }
        }

        // Get all published ingredients for dropdowns
        $ingredients = Ingredient::find()
            ->where(['status' => Ingredient::STATUS_PUBLISHED])
            ->orderBy(['name' => SORT_ASC])
            ->all();

        return $this->render('finder', [
            'ingredients' => $ingredients,
            'ingredientA' => $ingredientA,
            'ingredientB' => $ingredientB,
            'comparison' => $comparison,
        ]);
    }

    /**
     * Advanced search with nutrition filters
     * @return string
     */
    public function actionSearch()
    {
        $searchModel = new IngredientSearch();
        $dataProvider = $searchModel->advancedSearch(Yii::$app->request->queryParams);

        return $this->render('search', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Find ingredient by slug
     * @param string $slug
     * @return Ingredient
     * @throws NotFoundHttpException
     */
    protected function findModelBySlug($slug)
    {
        $model = Ingredient::find()
            ->where(['slug' => $slug, 'status' => Ingredient::STATUS_PUBLISHED])
            ->one();

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested ingredient does not exist.');
    }
}
