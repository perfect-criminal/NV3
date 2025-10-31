<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Ingredient;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\IngredientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ingredients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ingredient-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Ingredient', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'attribute' => 'category',
                'filter' => Ingredient::getCategories(),
                'value' => function ($model) {
                    return Ingredient::getCategories()[$model->category] ?? $model->category;
                },
            ],
            [
                'attribute' => 'protein',
                'label' => 'Protein (g)',
                'format' => ['decimal', 2],
            ],
            [
                'attribute' => 'calories',
                'format' => ['decimal', 2],
            ],
            [
                'attribute' => 'status',
                'filter' => Ingredient::getStatuses(),
                'value' => function ($model) {
                    $statusClass = [
                        Ingredient::STATUS_DRAFT => 'label-warning',
                        Ingredient::STATUS_PUBLISHED => 'label-success',
                        Ingredient::STATUS_ARCHIVED => 'label-default',
                    ];
                    $class = $statusClass[$model->status] ?? 'label-default';
                    return '<span class="label ' . $class . '">' . strtoupper($model->status) . '</span>';
                },
                'format' => 'raw',
            ],
            'view_count',
            'comparison_count',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
