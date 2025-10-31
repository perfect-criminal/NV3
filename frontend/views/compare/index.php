<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Compare Vegan Ingredients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="compare-index">

    <div class="jumbotron">
        <h1>Compare Vegan Ingredients</h1>
        <p class="lead">
            Make informed decisions about your plant-based diet with our data-driven ingredient comparisons.
            Compare nutrition, taste, cost, and sustainability.
        </p>
    </div>

    <h2>Popular Comparisons</h2>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('_comparison_card', ['model' => $model]);
        },
        'layout' => '<div class="row">{items}</div>{pager}',
        'summary' => '<p class="text-muted">{count} comparisons found</p>',
    ]); ?>

</div>
