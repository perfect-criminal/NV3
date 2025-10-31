<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\Comparison;
use common\models\Ingredient;
use yii\data\ActiveDataProvider;

/**
 * CompareController handles comparison display for frontend
 */
class CompareController extends Controller
{
    /**
     * Displays all published comparisons
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Comparison::find()
                ->where(['status' => Comparison::STATUS_PUBLISHED])
                ->orderBy(['view_count' => SORT_DESC, 'created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single comparison
     * @param string $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($slug)
    {
        $model = $this->findModelBySlug($slug);

        // Increment view count
        $model->updateCounters(['view_count' => 1]);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Mark comparison as helpful
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionHelpful($id)
    {
        $model = Comparison::findOne($id);

        if ($model && $model->status === Comparison::STATUS_PUBLISHED) {
            $model->updateCounters(['helpful_count' => 1]);
            Yii::$app->session->setFlash('success', 'Thanks for your feedback!');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    /**
     * Mark comparison as not helpful
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionNotHelpful($id)
    {
        $model = Comparison::findOne($id);

        if ($model && $model->status === Comparison::STATUS_PUBLISHED) {
            $model->updateCounters(['not_helpful_count' => 1]);
            Yii::$app->session->setFlash('info', 'Thanks for your feedback!');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    /**
     * Find comparison by slug
     * @param string $slug
     * @return Comparison
     * @throws NotFoundHttpException
     */
    protected function findModelBySlug($slug)
    {
        $model = Comparison::find()
            ->where(['slug' => $slug, 'status' => Comparison::STATUS_PUBLISHED])
            ->one();

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested comparison does not exist.');
    }
}
