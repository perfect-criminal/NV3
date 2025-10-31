<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use common\models\Ingredient;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\IngredientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Browse Vegan Ingredients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ingredient-index">

    <div class="jumbotron">
        <h1>Vegan Ingredient Database</h1>
        <p class="lead">
            Explore our comprehensive database of plant-based ingredients with detailed nutrition information,
            cooking tips, and sustainability data.
        </p>
    </div>

    <!-- Quick Search and Filters -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-search"></span> Search & Filter
            </h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['index']]); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($searchModel, 'name')->textInput(['placeholder' => 'Search by name...']) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($searchModel, 'category')->dropDownList(
                        Ingredient::getCategories(),
                        ['prompt' => 'All Categories']
                    ) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($searchModel, 'availability')->dropDownList(
                        Ingredient::getAvailabilityOptions(),
                        ['prompt' => 'All Availability']
                    ) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Advanced Search', ['search'], ['class' => 'btn btn-info']) ?>
                <?= Html::a('Reset', ['index'], ['class' => 'btn btn-default']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <!-- Browse by Category -->
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-th"></span> Browse by Category
            </h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <?php foreach (Ingredient::getCategories() as $key => $label): ?>
                    <div class="col-md-3 col-sm-4 col-xs-6" style="margin-bottom: 10px;">
                        <?= Html::a(
                            '<span class="glyphicon glyphicon-chevron-right"></span> ' . Html::encode($label),
                            ['category', 'category' => $key],
                            ['class' => 'btn btn-default btn-block']
                        ) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <h2>All Ingredients</h2>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_ingredient_card',
        'layout' => '<div class="row">{items}</div>{pager}',
        'summary' => '<p class="text-muted">{count} ingredients found. Sort by: {sorterLink}</p>',
        'sorterHeader' => '',
    ]); ?>

</div>
