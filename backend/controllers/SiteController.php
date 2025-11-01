<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\Ingredient;
use common\models\Comparison;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // Gather statistics
        $stats = [
            'totalIngredients' => Ingredient::find()->count(),
            'publishedIngredients' => Ingredient::find()->where(['status' => Ingredient::STATUS_PUBLISHED])->count(),
            'draftIngredients' => Ingredient::find()->where(['status' => Ingredient::STATUS_DRAFT])->count(),
            'totalComparisons' => Comparison::find()->count(),
            'publishedComparisons' => Comparison::find()->where(['status' => Comparison::STATUS_PUBLISHED])->count(),
            'aiGeneratedComparisons' => Comparison::find()->where(['ai_generated' => 1])->count(),
        ];

        // Get recent ingredients
        $recentIngredients = Ingredient::find()
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();

        // Get recent comparisons
        $recentComparisons = Comparison::find()
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();

        return $this->render('index', [
            'stats' => $stats,
            'recentIngredients' => $recentIngredients,
            'recentComparisons' => $recentComparisons,
        ]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
