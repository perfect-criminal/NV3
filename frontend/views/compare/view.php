<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Comparison */

$this->title = $model->meta_title ?: $model->title;
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);
$this->params['breadcrumbs'][] = ['label' => 'Compare', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Schema.org JSON-LD
if ($model->schema_data) {
    $this->registerJs(
        'var schema = ' . json_encode($model->schema_data) . ';',
        \yii\web\View::POS_HEAD,
        'schema-jsonld'
    );
}
?>
<div class="compare-view">

    <!-- Header -->
    <div class="page-header">
        <h1><?= Html::encode($model->title) ?></h1>
        <p class="lead">
            <span class="label label-info"><?= Html::encode($model->ingredientA->category ?? 'N/A') ?></span>
            <strong><?= Html::encode($model->ingredientA->name ?? 'N/A') ?></strong>
            vs
            <span class="label label-info"><?= Html::encode($model->ingredientB->category ?? 'N/A') ?></span>
            <strong><?= Html::encode($model->ingredientB->name ?? 'N/A') ?></strong>
        </p>
    </div>

    <!-- Summary -->
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><span class="glyphicon glyphicon-info-sign"></span> Quick Summary (TL;DR)</h3>
        </div>
        <div class="panel-body">
            <p class="lead"><?= nl2br(Html::encode($model->summary)) ?></p>
            <?php if ($model->winner_category): ?>
                <p><strong>Winner:</strong> <?= Html::encode($model->winner_category) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Key Differences -->
    <?php if ($model->key_differences): ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-stats"></span> Key Differences</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="bg-primary">
                            <tr>
                                <th width="25%">Category</th>
                                <th width="37.5%"><?= Html::encode($model->ingredientA->name ?? 'Ingredient A') ?></th>
                                <th width="37.5%"><?= Html::encode($model->ingredientB->name ?? 'Ingredient B') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($model->key_differences as $diff): ?>
                                <tr>
                                    <td><strong><?= Html::encode($diff['category'] ?? 'N/A') ?></strong></td>
                                    <td><?= Html::encode($diff['ingredient_a'] ?? 'N/A') ?></td>
                                    <td><?= Html::encode($diff['ingredient_b'] ?? 'N/A') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Introduction -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><span class="glyphicon glyphicon-book"></span> Introduction</h3>
        </div>
        <div class="panel-body">
            <div style="font-size: 16px; line-height: 1.6;">
                <?= nl2br(Html::encode($model->introduction)) ?>
            </div>
        </div>
    </div>

    <!-- Nutrition Comparison Chart (if comparison_data exists) -->
    <?php if ($model->comparison_data && isset($model->comparison_data['nutrition'])): ?>
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-heart"></span> Nutrition Comparison</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <?php foreach ($model->comparison_data['nutrition'] as $nutrient => $data): ?>
                        <?php if (isset($data['ingredient_a'], $data['ingredient_b'])): ?>
                            <div class="col-md-4">
                                <div class="text-center" style="margin-bottom: 20px;">
                                    <h4><?= ucfirst($nutrient) ?></h4>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success" style="width: 50%">
                                            <?= Html::encode($model->ingredientA->name) ?>: <?= $data['ingredient_a'] ?>
                                        </div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-info" style="width: 50%">
                                            <?= Html::encode($model->ingredientB->name) ?>: <?= $data['ingredient_b'] ?>
                                        </div>
                                    </div>
                                    <?php if (isset($data['winner'])): ?>
                                        <small class="text-muted">
                                            Winner: <?= $data['winner'] === 'a' ? $model->ingredientA->name : $model->ingredientB->name ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Recommendations -->
    <div class="panel panel-warning">
        <div class="panel-heading">
            <h3 class="panel-title"><span class="glyphicon glyphicon-star"></span> Recommendations</h3>
        </div>
        <div class="panel-body">
            <div style="font-size: 16px; line-height: 1.6;">
                <?= nl2br(Html::encode($model->recommendations)) ?>
            </div>
        </div>
    </div>

    <!-- Conclusion -->
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><span class="glyphicon glyphicon-check"></span> The Verdict</h3>
        </div>
        <div class="panel-body">
            <div style="font-size: 16px; line-height: 1.6;">
                <?= nl2br(Html::encode($model->conclusion)) ?>
            </div>
        </div>
    </div>

    <!-- Feedback -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Was this comparison helpful?</h3>
        </div>
        <div class="panel-body text-center">
            <p>
                <?= Html::a(
                    '<span class="glyphicon glyphicon-thumbs-up"></span> Yes, helpful (' . $model->helpful_count . ')',
                    ['helpful', 'id' => $model->id],
                    ['class' => 'btn btn-success btn-lg', 'data-method' => 'post']
                ) ?>
                <?= Html::a(
                    '<span class="glyphicon glyphicon-thumbs-down"></span> Not helpful (' . $model->not_helpful_count . ')',
                    ['not-helpful', 'id' => $model->id],
                    ['class' => 'btn btn-default btn-lg', 'data-method' => 'post']
                ) ?>
            </p>
            <small class="text-muted">
                <?= $model->getHelpfulPercentage() ?>% found this comparison helpful
            </small>
        </div>
    </div>

    <!-- View Stats -->
    <p class="text-muted text-center">
        <small>
            <span class="glyphicon glyphicon-eye-open"></span> <?= number_format($model->view_count) ?> views
            <?php if ($model->ai_generated): ?>
                &nbsp;&nbsp;<span class="glyphicon glyphicon-cog"></span> AI-Generated
            <?php endif; ?>
        </small>
    </p>

</div>

<style>
.comparison-card {
    transition: box-shadow 0.3s;
}

.comparison-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.table-hover tbody tr:hover {
    background-color: #f5f5f5;
}
</style>
