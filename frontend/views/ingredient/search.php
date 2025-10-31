<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use common\models\Ingredient;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\IngredientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Advanced Ingredient Search';
$this->params['breadcrumbs'][] = ['label' => 'Ingredients', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Advanced Search';
?>
<div class="ingredient-search">

    <div class="page-header">
        <h1>Advanced Ingredient Search</h1>
        <p class="lead">Find ingredients by specific nutrition criteria</p>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-filter"></span> Filter Ingredients
            </h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['search']]); ?>

            <!-- Basic Filters -->
            <h4>Basic Filters</h4>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($searchModel, 'name')->textInput(['placeholder' => 'Search by name...']) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($searchModel, 'category')->dropDownList(
                        Ingredient::getCategories(),
                        ['prompt' => 'All Categories']
                    ) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($searchModel, 'availability')->dropDownList(
                        Ingredient::getAvailabilityOptions(),
                        ['prompt' => 'All Availability']
                    ) ?>
                </div>
            </div>

            <hr>

            <!-- Quick Filters -->
            <h4>Quick Filters</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="checkbox">
                        <label>
                            <?= Html::activeCheckbox($searchModel, 'highProtein') ?>
                            High Protein (&gt;10g per 100g)
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="checkbox">
                        <label>
                            <?= Html::activeCheckbox($searchModel, 'lowCalorie') ?>
                            Low Calorie (&lt;100 per 100g)
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="checkbox">
                        <label>
                            <?= Html::activeCheckbox($searchModel, 'highFiber') ?>
                            High Fiber (&gt;5g per 100g)
                        </label>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Dietary Flags -->
            <h4>Dietary Preferences</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="checkbox">
                        <label>
                            <?= Html::activeCheckbox($searchModel, 'glutenFree') ?>
                            Gluten-Free
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="checkbox">
                        <label>
                            <?= Html::activeCheckbox($searchModel, 'soyFree') ?>
                            Soy-Free
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="checkbox">
                        <label>
                            <?= Html::activeCheckbox($searchModel, 'nutFree') ?>
                            Nut-Free
                        </label>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Nutrition Range Filters -->
            <h4>Nutrition Ranges (per 100g)</h4>
            <div class="row">
                <div class="col-md-6">
                    <label>Protein (g)</label>
                    <div class="row">
                        <div class="col-xs-6">
                            <?= Html::activeTextInput($searchModel, 'minProtein', [
                                'class' => 'form-control',
                                'placeholder' => 'Min',
                                'type' => 'number',
                                'step' => '0.1'
                            ]) ?>
                        </div>
                        <div class="col-xs-6">
                            <?= Html::activeTextInput($searchModel, 'maxProtein', [
                                'class' => 'form-control',
                                'placeholder' => 'Max',
                                'type' => 'number',
                                'step' => '0.1'
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Calories</label>
                    <div class="row">
                        <div class="col-xs-6">
                            <?= Html::activeTextInput($searchModel, 'minCalories', [
                                'class' => 'form-control',
                                'placeholder' => 'Min',
                                'type' => 'number'
                            ]) ?>
                        </div>
                        <div class="col-xs-6">
                            <?= Html::activeTextInput($searchModel, 'maxCalories', [
                                'class' => 'form-control',
                                'placeholder' => 'Max',
                                'type' => 'number'
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 15px;">
                <div class="col-md-4">
                    <?= $form->field($searchModel, 'minFiber')->textInput([
                        'type' => 'number',
                        'step' => '0.1',
                        'placeholder' => 'Minimum fiber (g)'
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($searchModel, 'minIron')->textInput([
                        'type' => 'number',
                        'step' => '0.1',
                        'placeholder' => 'Minimum iron (% DV)'
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($searchModel, 'minCalcium')->textInput([
                        'type' => 'number',
                        'step' => '0.1',
                        'placeholder' => 'Minimum calcium (% DV)'
                    ]) ?>
                </div>
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <?= Html::submitButton('<span class="glyphicon glyphicon-search"></span> Search', ['class' => 'btn btn-primary btn-lg']) ?>
                <?= Html::a('Reset', ['search'], ['class' => 'btn btn-default btn-lg']) ?>
                <?= Html::a('Simple Search', ['index'], ['class' => 'btn btn-link']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <h2>Search Results</h2>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_ingredient_card',
        'layout' => '<p class="text-muted">{summary}</p><div class="row">{items}</div>{pager}',
    ]); ?>

</div>
