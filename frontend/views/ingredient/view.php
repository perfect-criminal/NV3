<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Ingredient */
/* @var $relatedComparisons array */
/* @var $similarIngredients array */
/* @var $substitutes array */

$this->title = $model->meta_title ?: $model->name;
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description ?: $model->summary]);
if ($model->meta_keywords) {
    $this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);
}
$this->params['breadcrumbs'][] = ['label' => 'Ingredients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ingredient-view">

    <!-- Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-8">
                <h1><?= Html::encode($model->name) ?></h1>
                <?php if ($model->scientific_name): ?>
                    <p class="lead"><em><?= Html::encode($model->scientific_name) ?></em></p>
                <?php endif; ?>
                <p>
                    <span class="label label-primary"><?= Html::encode(common\models\Ingredient::getCategories()[$model->category] ?? $model->category) ?></span>
                    <?php if ($model->subcategory): ?>
                        <span class="label label-default"><?= Html::encode($model->subcategory) ?></span>
                    <?php endif; ?>
                    <?php if ($model->data_verified): ?>
                        <span class="label label-success"><span class="glyphicon glyphicon-check"></span> Verified</span>
                    <?php endif; ?>
                </p>
            </div>
            <?php if ($model->featuredImage): ?>
                <div class="col-md-4">
                    <img src="<?= Html::encode($model->featuredImage->getUrl()) ?>"
                         alt="<?= Html::encode($model->name) ?>"
                         class="img-responsive img-thumbnail">
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Summary -->
    <?php if ($model->summary): ?>
        <div class="alert alert-info">
            <h4><span class="glyphicon glyphicon-info-sign"></span> Quick Summary</h4>
            <p class="lead"><?= Html::encode($model->summary) ?></p>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <!-- Description -->
            <?php if ($model->description): ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">About <?= Html::encode($model->name) ?></h3>
                    </div>
                    <div class="panel-body">
                        <p style="font-size: 16px; line-height: 1.6;">
                            <?= nl2br(Html::encode($model->description)) ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Nutrition Information -->
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-heart"></span> Nutrition Facts (per 100g)</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tr>
                                    <th width="50%">Calories</th>
                                    <td><?= $model->calories ? round($model->calories) : 'N/A' ?></td>
                                </tr>
                                <tr>
                                    <th>Protein</th>
                                    <td><?= $model->protein ? round($model->protein, 1) . 'g' : 'N/A' ?></td>
                                </tr>
                                <tr>
                                    <th>Fat</th>
                                    <td><?= $model->fat ? round($model->fat, 1) . 'g' : 'N/A' ?></td>
                                </tr>
                                <tr>
                                    <th>Carbohydrates</th>
                                    <td><?= $model->carbs ? round($model->carbs, 1) . 'g' : 'N/A' ?></td>
                                </tr>
                                <tr>
                                    <th>Fiber</th>
                                    <td><?= $model->fiber ? round($model->fiber, 1) . 'g' : 'N/A' ?></td>
                                </tr>
                                <tr>
                                    <th>Sugar</th>
                                    <td><?= $model->sugar ? round($model->sugar, 1) . 'g' : 'N/A' ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Vitamins & Minerals (% Daily Value)</h5>
                            <table class="table table-striped">
                                <tr>
                                    <th width="50%">Vitamin B12</th>
                                    <td><?= $model->vitamin_b12 ? round($model->vitamin_b12, 1) . '%' : 'N/A' ?></td>
                                </tr>
                                <tr>
                                    <th>Vitamin D</th>
                                    <td><?= $model->vitamin_d ? round($model->vitamin_d, 1) . '%' : 'N/A' ?></td>
                                </tr>
                                <tr>
                                    <th>Iron</th>
                                    <td><?= $model->iron ? round($model->iron, 1) . '%' : 'N/A' ?></td>
                                </tr>
                                <tr>
                                    <th>Calcium</th>
                                    <td><?= $model->calcium ? round($model->calcium, 1) . '%' : 'N/A' ?></td>
                                </tr>
                                <tr>
                                    <th>Zinc</th>
                                    <td><?= $model->zinc ? round($model->zinc, 1) . '%' : 'N/A' ?></td>
                                </tr>
                                <tr>
                                    <th>Omega-3</th>
                                    <td><?= $model->omega3 ? round($model->omega3, 1) . 'g' : 'N/A' ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Taste & Texture -->
            <?php if ($model->taste_profile || $model->texture): ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-cutlery"></span> Taste & Texture</h3>
                    </div>
                    <div class="panel-body">
                        <?php if ($model->taste_profile): ?>
                            <p><strong>Taste:</strong> <?= Html::encode($model->taste_profile) ?></p>
                        <?php endif; ?>
                        <?php if ($model->texture): ?>
                            <p><strong>Texture:</strong> <?= Html::encode($model->texture) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Cooking & Usage -->
            <?php if ($model->storage_tips || $model->preparation_tips || $model->common_uses): ?>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-book"></span> Cooking & Usage Tips</h3>
                    </div>
                    <div class="panel-body">
                        <?php if (is_array($model->common_uses) && !empty($model->common_uses)): ?>
                            <p><strong>Common Uses:</strong> <?= implode(', ', array_map('ucfirst', $model->common_uses)) ?></p>
                        <?php endif; ?>

                        <?php if ($model->preparation_tips): ?>
                            <h5>Preparation Tips:</h5>
                            <p><?= nl2br(Html::encode($model->preparation_tips)) ?></p>
                        <?php endif; ?>

                        <?php if ($model->storage_tips): ?>
                            <h5>Storage Tips:</h5>
                            <p><?= nl2br(Html::encode($model->storage_tips)) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <!-- Quick Facts -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Quick Facts</h3>
                </div>
                <div class="panel-body">
                    <?php if ($model->origin): ?>
                        <p><strong>Origin:</strong> <?= Html::encode($model->origin) ?></p>
                    <?php endif; ?>
                    <?php if ($model->season): ?>
                        <p><strong>Season:</strong> <?= Html::encode($model->season) ?></p>
                    <?php endif; ?>
                    <p><strong>Availability:</strong>
                        <?= Html::encode(common\models\Ingredient::getAvailabilityOptions()[$model->availability] ?? $model->availability) ?>
                    </p>
                    <?php if ($model->avg_price_per_kg): ?>
                        <p><strong>Avg Price:</strong> $<?= number_format($model->avg_price_per_kg, 2) ?>/kg</p>
                    <?php endif; ?>
                    <?php if ($model->sustainability_score): ?>
                        <p><strong>Sustainability Score:</strong> <?= $model->sustainability_score ?>/10</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Substitutes -->
            <?php if (!empty($substitutes)): ?>
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">Possible Substitutes</h3>
                    </div>
                    <div class="panel-body">
                        <ul>
                            <?php foreach ($substitutes as $substitute): ?>
                                <li>
                                    <?= Html::a(Html::encode($substitute->name), ['view', 'slug' => $substitute->slug]) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Related Comparisons -->
            <?php if (!empty($relatedComparisons)): ?>
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title">Compare With</h3>
                    </div>
                    <div class="panel-body">
                        <ul>
                            <?php foreach ($relatedComparisons as $comparison): ?>
                                <li>
                                    <?= Html::a(
                                        Html::encode($comparison->title),
                                        ['/compare/view', 'slug' => $comparison->slug]
                                    ) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?= Html::a('Find More Comparisons', ['finder'], ['class' => 'btn btn-success btn-block btn-sm']) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Similar Ingredients -->
            <?php if (!empty($similarIngredients)): ?>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Similar Ingredients</h3>
                    </div>
                    <div class="panel-body">
                        <ul>
                            <?php foreach ($similarIngredients as $similar): ?>
                                <li>
                                    <?= Html::a(Html::encode($similar->name), ['view', 'slug' => $similar->slug]) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Stats -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Statistics</h3>
                </div>
                <div class="panel-body">
                    <p>
                        <span class="glyphicon glyphicon-eye-open"></span>
                        <?= number_format($model->view_count) ?> views
                    </p>
                    <p>
                        <span class="glyphicon glyphicon-transfer"></span>
                        <?= number_format($model->comparison_count) ?> comparisons
                    </p>
                    <?php if ($model->rating): ?>
                        <p>
                            <span class="glyphicon glyphicon-star"></span>
                            <?= round($model->rating, 1) ?>/5
                            (<?= number_format($model->rating_count) ?> ratings)
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>
