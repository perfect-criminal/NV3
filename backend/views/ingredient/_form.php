<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Ingredient;

/* @var $this yii\web\View */
/* @var $model common\models\Ingredient */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ingredient-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Basic Information</h3>
                </div>
                <div class="panel-body">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'category')->dropDownList(Ingredient::getCategories(), ['prompt' => 'Select Category']) ?>

                    <?= $form->field($model, 'subcategory')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'scientific_name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'origin')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'summary')->textarea(['rows' => 2, 'maxlength' => 500]) ?>

                    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'taste_profile')->textInput(['maxlength' => true, 'placeholder' => 'e.g., Mild, slightly nutty']) ?>

                    <?= $form->field($model, 'texture')->textInput(['maxlength' => true, 'placeholder' => 'e.g., Firm, soft, silky']) ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Nutrition Information (per 100g)</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'calories')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                            <?= $form->field($model, 'protein')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                            <?= $form->field($model, 'fat')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                            <?= $form->field($model, 'carbs')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'fiber')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                            <?= $form->field($model, 'sugar')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                            <?= $form->field($model, 'sodium')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                        </div>
                    </div>

                    <h4>Vitamins & Minerals (% Daily Value)</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'vitamin_b12')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                            <?= $form->field($model, 'vitamin_d')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                            <?= $form->field($model, 'iron')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'calcium')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                            <?= $form->field($model, 'zinc')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                            <?= $form->field($model, 'omega3')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Cooking & Usage</h3>
                </div>
                <div class="panel-body">
                    <?= $form->field($model, 'storage_tips')->textarea(['rows' => 3]) ?>

                    <?= $form->field($model, 'preparation_tips')->textarea(['rows' => 3]) ?>

                    <?= $form->field($model, 'season')->textInput(['maxlength' => true, 'placeholder' => 'e.g., Summer, Year-round']) ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Sustainability</h3>
                </div>
                <div class="panel-body">
                    <?= $form->field($model, 'sustainability_score')->textInput(['type' => 'number', 'min' => 1, 'max' => 10]) ?>

                    <?= $form->field($model, 'environmental_impact')->textarea(['rows' => 3]) ?>

                    <?= $form->field($model, 'avg_price_per_kg')->textInput(['type' => 'number', 'step' => '0.01']) ?>

                    <?= $form->field($model, 'availability')->dropDownList(Ingredient::getAvailabilityOptions(), ['prompt' => 'Select Availability']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Publishing</h3>
                </div>
                <div class="panel-body">
                    <?= $form->field($model, 'status')->dropDownList(Ingredient::getStatuses()) ?>

                    <?= $form->field($model, 'ai_generated')->checkbox() ?>

                    <?= $form->field($model, 'data_verified')->checkbox() ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">SEO</h3>
                </div>
                <div class="panel-body">
                    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => 255]) ?>

                    <?= $form->field($model, 'meta_description')->textarea(['rows' => 3, 'maxlength' => 500]) ?>

                    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => 500]) ?>
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
