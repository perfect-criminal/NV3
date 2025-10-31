<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Comparison;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ComparisonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Comparisons';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comparison-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Generate Comparison', ['generate'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Batch Generate', ['batch-generate'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Create Manually', ['create'], ['class' => 'btn btn-default']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(Html::encode($model->title), ['view', 'id' => $model->id]);
                },
            ],
            [
                'label' => 'Ingredients',
                'format' => 'raw',
                'value' => function ($model) {
                    $a = $model->ingredientA ? $model->ingredientA->name : 'N/A';
                    $b = $model->ingredientB ? $model->ingredientB->name : 'N/A';
                    return "<strong>{$a}</strong> vs <strong>{$b}</strong>";
                },
            ],
            [
                'attribute' => 'status',
                'filter' => Comparison::getStatuses(),
                'value' => function ($model) {
                    $statusClass = [
                        Comparison::STATUS_DRAFT => 'label-warning',
                        Comparison::STATUS_PUBLISHED => 'label-success',
                        Comparison::STATUS_ARCHIVED => 'label-default',
                    ];
                    $class = $statusClass[$model->status] ?? 'label-default';
                    return '<span class="label ' . $class . '">' . strtoupper($model->status) . '</span>';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'ai_generated',
                'filter' => ['1' => 'Yes', '0' => 'No'],
                'value' => function ($model) {
                    return $model->ai_generated ? '<span class="label label-info">AI</span>' : '<span class="label label-default">Manual</span>';
                },
                'format' => 'raw',
            ],
            'view_count',
            [
                'label' => 'Helpful',
                'value' => function ($model) {
                    return $model->helpful_count . ' / ' . ($model->helpful_count + $model->not_helpful_count);
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
