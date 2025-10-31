<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $ingredients array */

$this->title = 'Batch Generate Comparisons';
$this->params['breadcrumbs'][] = ['label' => 'Comparisons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comparison-batch-generate">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <strong>Batch Generation:</strong> Generate multiple comparisons at once. Select ingredient pairs and click generate.
        This is useful for quickly building up your comparison database.
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Ingredient Pairs to Compare</h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(['method' => 'post']); ?>

            <div id="pairs-container">
                <!-- Pair templates will be added here -->
            </div>

            <div class="form-group">
                <?= Html::button('Add Pair', ['class' => 'btn btn-default', 'id' => 'add-pair-btn']) ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Generate All Comparisons', ['class' => 'btn btn-success btn-lg']) ?>
                <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default btn-lg']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="panel panel-warning">
        <div class="panel-heading">
            <h3 class="panel-title">Quick Pairs Suggestions</h3>
        </div>
        <div class="panel-body">
            <p>Common comparison pairs for vegan ingredients:</p>
            <ul>
                <li>Tofu vs Tempeh</li>
                <li>Oat Milk vs Almond Milk</li>
                <li>Quinoa vs Rice</li>
                <li>Chickpeas vs Black Beans</li>
                <li>Chia Seeds vs Flax Seeds</li>
                <li>Seitan vs Tofu</li>
                <li>Cashews vs Almonds</li>
                <li>Kale vs Spinach</li>
            </ul>
        </div>
    </div>

</div>

<?php
$ingredientsJson = json_encode($ingredients);
$this->registerJs(<<<JS
    var ingredients = $ingredientsJson;
    var pairCounter = 0;

    function addPair() {
        pairCounter++;
        var html = '<div class="row pair-row" data-pair="' + pairCounter + '">' +
            '<div class="col-md-5">' +
                '<div class="form-group">' +
                    '<label>Ingredient A</label>' +
                    '<select name="pairs[' + pairCounter + '][ingredient_a_id]" class="form-control">' +
                        '<option value="">Select ingredient</option>';

        for (var id in ingredients) {
            html += '<option value="' + id + '">' + ingredients[id] + '</option>';
        }

        html += '</select>' +
                '</div>' +
            '</div>' +
            '<div class="col-md-5">' +
                '<div class="form-group">' +
                    '<label>Ingredient B</label>' +
                    '<select name="pairs[' + pairCounter + '][ingredient_b_id]" class="form-control">' +
                        '<option value="">Select ingredient</option>';

        for (var id in ingredients) {
            html += '<option value="' + id + '">' + ingredients[id] + '</option>';
        }

        html += '</select>' +
                '</div>' +
            '</div>' +
            '<div class="col-md-2">' +
                '<div class="form-group">' +
                    '<label>&nbsp;</label>' +
                    '<button type="button" class="btn btn-danger btn-block remove-pair-btn">Remove</button>' +
                '</div>' +
            '</div>' +
        '</div>';

        $('#pairs-container').append(html);
    }

    // Add initial pair
    addPair();

    // Add pair button
    $('#add-pair-btn').on('click', function() {
        addPair();
    });

    // Remove pair button
    $(document).on('click', '.remove-pair-btn', function() {
        $(this).closest('.pair-row').remove();
    });

    // Form validation
    $('form').on('beforeSubmit', function() {
        var valid = true;
        $('.pair-row').each(function() {
            var ingredientA = $(this).find('select[name*="ingredient_a_id"]').val();
            var ingredientB = $(this).find('select[name*="ingredient_b_id"]').val();

            if (!ingredientA || !ingredientB) {
                alert('Please select both ingredients for all pairs');
                valid = false;
                return false;
            }

            if (ingredientA === ingredientB) {
                alert('Please select different ingredients for each pair');
                valid = false;
                return false;
            }
        });

        return valid;
    });
JS
);
?>
