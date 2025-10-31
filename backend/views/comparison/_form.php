<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Comparison;
use common\models\Ingredient;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Comparison */
/* @var $form yii\widgets\ActiveForm */

$ingredients = ArrayHelper::map(
    Ingredient::find()->where(['status' => Ingredient::STATUS_PUBLISHED])->orderBy(['name' => SORT_ASC])->all(),
    'id',
    'name'
);
?>

<div class="comparison-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Select Ingredients</h3>
                </div>
                <div class="panel-body">
                    <?= $form->field($model, 'ingredient_a_id')->dropDownList($ingredients, ['prompt' => 'Select Ingredient A']) ?>

                    <?= $form->field($model, 'ingredient_b_id')->dropDownList($ingredients, ['prompt' => 'Select Ingredient B']) ?>

                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'winner_category')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Comparison Content</h3>
                </div>
                <div class="panel-body">
                    <?= $form->field($model, 'summary')->textarea(['rows' => 4]) ?>

                    <?= $form->field($model, 'introduction')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'recommendations')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'conclusion')->textarea(['rows' => 6]) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Publishing</h3>
                </div>
                <div class="panel-body">
                    <?= $form->field($model, 'status')->dropDownList(Comparison::getStatuses()) ?>

                    <?= $form->field($model, 'ai_generated')->checkbox() ?>

                    <?= $form->field($model, 'ai_model')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">SEO</h3>
                </div>
                <div class="panel-body">
                    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => 255]) ?>

                    <?= $form->field($model, 'meta_description')->textarea(['rows' => 3, 'maxlength' => 500]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-lg']) ?>
        <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
