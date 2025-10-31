<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $category string */
/* @var $categoryName string */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $categoryName . ' - Vegan Ingredients';
$this->params['breadcrumbs'][] = ['label' => 'Ingredients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $categoryName;
?>
<div class="ingredient-category">

    <div class="page-header">
        <h1>
            <?= Html::encode($categoryName) ?>
            <small><?= $dataProvider->totalCount ?> ingredients</small>
        </h1>
    </div>

    <div class="alert alert-info">
        <p>
            <strong>Browsing:</strong> <?= Html::encode($categoryName) ?> ingredients.
            <?= Html::a('View all categories', ['index'], ['class' => 'btn btn-xs btn-default']) ?>
        </p>
    </div>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_ingredient_card',
        'layout' => '<div class="row">{items}</div>{pager}',
        'summary' => '',
    ]); ?>

</div>
