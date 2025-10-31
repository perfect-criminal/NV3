<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $ingredients array */
/* @var $ingredientA common\models\Ingredient */
/* @var $ingredientB common\models\Ingredient */
/* @var $comparison common\models\Comparison */

$this->title = 'Comparison Finder';
$this->params['breadcrumbs'][] = ['label' => 'Ingredients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ingredient-finder">

    <div class="jumbotron text-center">
        <h1>
            <span class="glyphicon glyphicon-transfer"></span>
            Comparison Finder
        </h1>
        <p class="lead">
            Compare any two vegan ingredients side-by-side to find the best option for your needs
        </p>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Select Ingredients to Compare</h3>
        </div>
        <div class="panel-body">
            <form method="get" action="<?= Url::to(['finder']) ?>" id="comparison-finder-form">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="ingredient-a">Ingredient A</label>
                            <select name="ingredient_a" id="ingredient-a" class="form-control" required>
                                <option value="">Select first ingredient</option>
                                <?php foreach ($ingredients as $ingredient): ?>
                                    <option value="<?= $ingredient->id ?>"
                                        <?= $ingredientA && $ingredientA->id == $ingredient->id ? 'selected' : '' ?>>
                                        <?= Html::encode($ingredient->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 text-center" style="padding-top: 30px;">
                        <h2><strong>VS</strong></h2>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="ingredient-b">Ingredient B</label>
                            <select name="ingredient_b" id="ingredient-b" class="form-control" required>
                                <option value="">Select second ingredient</option>
                                <?php foreach ($ingredients as $ingredient): ?>
                                    <option value="<?= $ingredient->id ?>"
                                        <?= $ingredientB && $ingredientB->id == $ingredient->id ? 'selected' : '' ?>>
                                        <?= Html::encode($ingredient->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success btn-lg">
                        <span class="glyphicon glyphicon-search"></span> Find Comparison
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($ingredientA && $ingredientB): ?>
        <?php if ($comparison): ?>
            <!-- Comparison Found -->
            <div class="alert alert-success">
                <h4><span class="glyphicon glyphicon-ok"></span> Comparison Found!</h4>
                <p>We have a detailed comparison for these ingredients.</p>
            </div>

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Html::encode($comparison->title) ?></h3>
                </div>
                <div class="panel-body">
                    <p><?= Html::encode($comparison->summary) ?></p>

                    <?= Html::a(
                        'View Full Comparison <span class="glyphicon glyphicon-chevron-right"></span>',
                        ['/compare/view', 'slug' => $comparison->slug],
                        ['class' => 'btn btn-success btn-lg btn-block']
                    ) ?>
                </div>
            </div>
        <?php else: ?>
            <!-- Quick Comparison -->
            <div class="alert alert-info">
                <h4><span class="glyphicon glyphicon-info-sign"></span> Quick Comparison</h4>
                <p>We don't have a detailed comparison yet, but here's a quick side-by-side view.</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?= Html::encode($ingredientA->name) ?></h3>
                        </div>
                        <div class="panel-body">
                            <p><strong>Category:</strong> <?= Html::encode($ingredientA->category) ?></p>

                            <h5>Nutrition (per 100g):</h5>
                            <ul>
                                <?php if ($ingredientA->calories): ?>
                                    <li><strong>Calories:</strong> <?= round($ingredientA->calories) ?></li>
                                <?php endif; ?>
                                <?php if ($ingredientA->protein): ?>
                                    <li><strong>Protein:</strong> <?= round($ingredientA->protein, 1) ?>g</li>
                                <?php endif; ?>
                                <?php if ($ingredientA->fat): ?>
                                    <li><strong>Fat:</strong> <?= round($ingredientA->fat, 1) ?>g</li>
                                <?php endif; ?>
                                <?php if ($ingredientA->carbs): ?>
                                    <li><strong>Carbs:</strong> <?= round($ingredientA->carbs, 1) ?>g</li>
                                <?php endif; ?>
                                <?php if ($ingredientA->fiber): ?>
                                    <li><strong>Fiber:</strong> <?= round($ingredientA->fiber, 1) ?>g</li>
                                <?php endif; ?>
                            </ul>

                            <?= Html::a('View Details', ['view', 'slug' => $ingredientA->slug], ['class' => 'btn btn-primary btn-block']) ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?= Html::encode($ingredientB->name) ?></h3>
                        </div>
                        <div class="panel-body">
                            <p><strong>Category:</strong> <?= Html::encode($ingredientB->category) ?></p>

                            <h5>Nutrition (per 100g):</h5>
                            <ul>
                                <?php if ($ingredientB->calories): ?>
                                    <li><strong>Calories:</strong> <?= round($ingredientB->calories) ?></li>
                                <?php endif; ?>
                                <?php if ($ingredientB->protein): ?>
                                    <li><strong>Protein:</strong> <?= round($ingredientB->protein, 1) ?>g</li>
                                <?php endif; ?>
                                <?php if ($ingredientB->fat): ?>
                                    <li><strong>Fat:</strong> <?= round($ingredientB->fat, 1) ?>g</li>
                                <?php endif; ?>
                                <?php if ($ingredientB->carbs): ?>
                                    <li><strong>Carbs:</strong> <?= round($ingredientB->carbs, 1) ?>g</li>
                                <?php endif; ?>
                                <?php if ($ingredientB->fiber): ?>
                                    <li><strong>Fiber:</strong> <?= round($ingredientB->fiber, 1) ?>g</li>
                                <?php endif; ?>
                            </ul>

                            <?= Html::a('View Details', ['view', 'slug' => $ingredientB->slug], ['class' => 'btn btn-info btn-block']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-warning text-center">
                <p><strong>Want a detailed comparison?</strong> We're constantly adding new comparisons!</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Popular Comparisons -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Popular Comparisons</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <ul>
                        <li><?= Html::a('Tofu vs Tempeh', ['/compare/view', 'slug' => 'tofu-vs-tempeh']) ?></li>
                        <li><?= Html::a('Oat Milk vs Almond Milk', ['/compare/view', 'slug' => 'oat-milk-vs-almond-milk']) ?></li>
                        <li><?= Html::a('Quinoa vs Rice', ['/compare/view', 'slug' => 'quinoa-vs-rice']) ?></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul>
                        <li><?= Html::a('Chickpeas vs Black Beans', ['/compare/view', 'slug' => 'chickpeas-vs-black-beans']) ?></li>
                        <li><?= Html::a('Chia Seeds vs Flax Seeds', ['/compare/view', 'slug' => 'chia-seeds-vs-flax-seeds']) ?></li>
                        <li><?= Html::a('Kale vs Spinach', ['/compare/view', 'slug' => 'kale-vs-spinach']) ?></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul>
                        <li><?= Html::a('Cashews vs Almonds', ['/compare/view', 'slug' => 'cashews-vs-almonds']) ?></li>
                        <li><?= Html::a('Seitan vs Tofu', ['/compare/view', 'slug' => 'seitan-vs-tofu']) ?></li>
                        <li><?= Html::a('View All Comparisons', ['/compare/index']) ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
$this->registerJs(<<<JS
    $('#comparison-finder-form').on('submit', function(e) {
        var ingredientA = $('#ingredient-a').val();
        var ingredientB = $('#ingredient-b').val();

        if (!ingredientA || !ingredientB) {
            alert('Please select both ingredients');
            e.preventDefault();
            return false;
        }

        if (ingredientA === ingredientB) {
            alert('Please select two different ingredients');
            e.preventDefault();
            return false;
        }
    });
JS
);
?>
