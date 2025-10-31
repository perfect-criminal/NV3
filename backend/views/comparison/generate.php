<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $ingredients array */
/* @var $ingredientA common\models\Ingredient */
/* @var $ingredientB common\models\Ingredient */
/* @var $comparison common\models\Comparison */
/* @var $error string */

$this->title = 'Generate Comparison';
$this->params['breadcrumbs'][] = ['label' => 'Comparisons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comparison-generate">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?= Html::encode($error) ?>
        </div>
    <?php endif; ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Select Ingredients to Compare</h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(['method' => 'post']); ?>

            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Ingredient A</label>
                        <?= Html::dropDownList(
                            'ingredient_a_id',
                            null,
                            $ingredients,
                            ['class' => 'form-control', 'prompt' => 'Select first ingredient']
                        ) ?>
                    </div>
                </div>

                <div class="col-md-2 text-center" style="padding-top: 25px;">
                    <strong style="font-size: 24px;">VS</strong>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <label>Ingredient B</label>
                        <?= Html::dropDownList(
                            'ingredient_b_id',
                            null,
                            $ingredients,
                            ['class' => 'form-control', 'prompt' => 'Select second ingredient']
                        ) ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Generate Comparison with AI', ['class' => 'btn btn-success btn-lg']) ?>
                <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default btn-lg']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">How It Works</h3>
        </div>
        <div class="panel-body">
            <ol>
                <li>Select two different ingredients from the dropdowns above</li>
                <li>Click "Generate Comparison with AI"</li>
                <li>Our AI will analyze both ingredients and create a comprehensive comparison including:
                    <ul>
                        <li>Nutritional breakdown and comparison</li>
                        <li>Taste and texture differences</li>
                        <li>Best uses for each ingredient</li>
                        <li>Cost and sustainability analysis</li>
                        <li>Recommendations based on different goals</li>
                    </ul>
                </li>
                <li>Review the generated comparison and publish it when ready</li>
            </ol>
            <p class="text-muted">
                <strong>Note:</strong> Make sure both ingredients have complete nutrition data for the best comparison results.
            </p>
        </div>
    </div>

</div>

<?php
$this->registerJs(<<<JS
    // Simple validation
    $('form').on('beforeSubmit', function() {
        var ingredientA = $('select[name="ingredient_a_id"]').val();
        var ingredientB = $('select[name="ingredient_b_id"]').val();

        if (!ingredientA || !ingredientB) {
            alert('Please select both ingredients');
            return false;
        }

        if (ingredientA === ingredientB) {
            alert('Please select two different ingredients');
            return false;
        }

        return true;
    });
JS
);
?>
