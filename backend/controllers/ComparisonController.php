<?php

namespace backend\controllers;

use Yii;
use common\models\Comparison;
use common\models\Ingredient;
use backend\models\ComparisonSearch;
use backend\services\ComparisonGeneratorService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * ComparisonController implements the CRUD actions for Comparison model.
 */
class ComparisonController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Comparison models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ComparisonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Comparison model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Comparison model manually.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Comparison();
        $model->status = Comparison::STATUS_DRAFT;
        $model->ai_generated = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Comparison created successfully.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Generate comparison using AI
     * @return string|\yii\web\Response
     */
    public function actionGenerate()
    {
        $ingredientA = null;
        $ingredientB = null;
        $comparison = null;
        $error = null;

        if (Yii::$app->request->isPost) {
            $ingredientAId = Yii::$app->request->post('ingredient_a_id');
            $ingredientBId = Yii::$app->request->post('ingredient_b_id');

            if ($ingredientAId && $ingredientBId) {
                if ($ingredientAId == $ingredientBId) {
                    $error = 'Please select two different ingredients.';
                } else {
                    $ingredientA = Ingredient::findOne($ingredientAId);
                    $ingredientB = Ingredient::findOne($ingredientBId);

                    if ($ingredientA && $ingredientB) {
                        // Check if comparison already exists
                        $existing = Comparison::find()
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
                            ->one();

                        if ($existing) {
                            Yii::$app->session->setFlash('warning', 'Comparison already exists. Redirecting to existing comparison.');
                            return $this->redirect(['view', 'id' => $existing->id]);
                        }

                        // Generate comparison using AI service
                        try {
                            $generatorService = new ComparisonGeneratorService();
                            $comparison = $generatorService->generateComparison($ingredientA, $ingredientB);

                            if ($comparison) {
                                Yii::$app->session->setFlash('success', 'Comparison generated successfully!');
                                return $this->redirect(['view', 'id' => $comparison->id]);
                            } else {
                                $error = 'Failed to generate comparison. Please try again.';
                            }
                        } catch (\Exception $e) {
                            $error = 'Error generating comparison: ' . $e->getMessage();
                            Yii::error($e->getMessage(), __METHOD__);
                        }
                    } else {
                        $error = 'One or both ingredients not found.';
                    }
                }
            } else {
                $error = 'Please select both ingredients.';
            }
        }

        // Get all published ingredients for dropdown
        $ingredients = ArrayHelper::map(
            Ingredient::find()
                ->where(['status' => Ingredient::STATUS_PUBLISHED])
                ->orderBy(['name' => SORT_ASC])
                ->all(),
            'id',
            'name'
        );

        return $this->render('generate', [
            'ingredients' => $ingredients,
            'ingredientA' => $ingredientA,
            'ingredientB' => $ingredientB,
            'comparison' => $comparison,
            'error' => $error,
        ]);
    }

    /**
     * Batch generate comparisons
     * @return string|\yii\web\Response
     */
    public function actionBatchGenerate()
    {
        $generated = [];
        $errors = [];

        if (Yii::$app->request->isPost) {
            $pairs = Yii::$app->request->post('pairs', []);

            foreach ($pairs as $pair) {
                if (isset($pair['ingredient_a_id'], $pair['ingredient_b_id'])) {
                    $ingredientA = Ingredient::findOne($pair['ingredient_a_id']);
                    $ingredientB = Ingredient::findOne($pair['ingredient_b_id']);

                    if ($ingredientA && $ingredientB) {
                        try {
                            $generatorService = new ComparisonGeneratorService();
                            $comparison = $generatorService->generateComparison($ingredientA, $ingredientB);

                            if ($comparison) {
                                $generated[] = $comparison;
                            }
                        } catch (\Exception $e) {
                            $errors[] = "{$ingredientA->name} vs {$ingredientB->name}: " . $e->getMessage();
                        }
                    }
                }
            }

            if (count($generated) > 0) {
                Yii::$app->session->setFlash('success', count($generated) . ' comparison(s) generated successfully!');
            }
            if (count($errors) > 0) {
                Yii::$app->session->setFlash('error', 'Errors: ' . implode(', ', $errors));
            }

            return $this->redirect(['index']);
        }

        // Get all published ingredients
        $ingredients = ArrayHelper::map(
            Ingredient::find()
                ->where(['status' => Ingredient::STATUS_PUBLISHED])
                ->orderBy(['name' => SORT_ASC])
                ->all(),
            'id',
            'name'
        );

        return $this->render('batch-generate', [
            'ingredients' => $ingredients,
        ]);
    }

    /**
     * Updates an existing Comparison model.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Comparison updated successfully.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Comparison model.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Comparison deleted successfully.');

        return $this->redirect(['index']);
    }

    /**
     * Publishes a comparison
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionPublish($id)
    {
        $model = $this->findModel($id);
        $model->status = Comparison::STATUS_PUBLISHED;
        $model->published_at = date('Y-m-d H:i:s');

        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Comparison published successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to publish comparison.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Archives a comparison
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionArchive($id)
    {
        $model = $this->findModel($id);
        $model->status = Comparison::STATUS_ARCHIVED;

        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Comparison archived successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to archive comparison.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Regenerate comparison content using AI
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionRegenerate($id)
    {
        $model = $this->findModel($id);

        try {
            $generatorService = new ComparisonGeneratorService();
            $updated = $generatorService->regenerateComparison($model);

            if ($updated) {
                Yii::$app->session->setFlash('success', 'Comparison content regenerated successfully.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to regenerate comparison.');
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Finds the Comparison model based on its primary key value.
     * @param int $id ID
     * @return Comparison the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comparison::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
