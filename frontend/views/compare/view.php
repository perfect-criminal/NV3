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

    <!-- Header with Visual Comparison -->
    <div class="comparison-card mb-5">
        <div class="comparison-header">
            <div class="row align-items-center">
                <div class="col-md-5 text-center">
                    <div class="mb-3">
                        <span class="badge badge-category">
                            <?= Html::encode($model->ingredientA->category ?? 'N/A') ?>
                        </span>
                    </div>
                    <h2 class="text-primary"><?= Html::encode($model->ingredientA->name ?? 'Ingredient A') ?></h2>
                </div>
                <div class="col-md-2 text-center">
                    <span class="comparison-vs">VS</span>
                </div>
                <div class="col-md-5 text-center">
                    <div class="mb-3">
                        <span class="badge badge-category">
                            <?= Html::encode($model->ingredientB->category ?? 'N/A') ?>
                        </span>
                    </div>
                    <h2 class="text-success"><?= Html::encode($model->ingredientB->name ?? 'Ingredient B') ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Summary (TL;DR) -->
    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">
                <span class="glyphicon glyphicon-flash"></span> Quick Summary (TL;DR)
            </h3>
        </div>
        <div class="card-body">
            <p class="lead mb-3"><?= nl2br(Html::encode($model->summary)) ?></p>
            <?php if ($model->winner_category): ?>
                <div class="alert alert-info mb-0">
                    <strong><span class="glyphicon glyphicon-trophy"></span> Winner:</strong>
                    <?= Html::encode($model->winner_category) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Key Differences -->
    <?php if ($model->key_differences): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="mb-0">
                    <span class="glyphicon glyphicon-stats"></span> Key Differences
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="25%">Category</th>
                                <th width="37.5%" class="text-primary">
                                    <?= Html::encode($model->ingredientA->name ?? 'Ingredient A') ?>
                                </th>
                                <th width="37.5%" class="text-success">
                                    <?= Html::encode($model->ingredientB->name ?? 'Ingredient B') ?>
                                </th>
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
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="mb-0">
                <span class="glyphicon glyphicon-book"></span> Introduction
            </h3>
        </div>
        <div class="card-body">
            <div style="font-size: 1.1rem; line-height: 1.8; color: #495057;">
                <?= nl2br(Html::encode($model->introduction)) ?>
            </div>
        </div>
    </div>

    <!-- Nutrition Comparison -->
    <?php if ($model->comparison_data && isset($model->comparison_data['nutrition'])): ?>
        <div class="card mb-4 border-success">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">
                    <span class="glyphicon glyphicon-heart"></span> Nutrition Comparison
                </h3>
            </div>
            <div class="card-body">
                <div class="nutrition-comparison">
                    <?php foreach ($model->comparison_data['nutrition'] as $nutrient => $data): ?>
                        <?php if (isset($data['ingredient_a'], $data['ingredient_b'])): ?>
                            <div class="nutrition-row">
                                <div class="nutrition-label">
                                    <?= ucwords(str_replace('_', ' ', $nutrient)) ?>
                                </div>
                                <div class="nutrition-value <?= isset($data['winner']) && $data['winner'] === 'a' ? 'winner' : '' ?>">
                                    <?= Html::encode($data['ingredient_a']) ?>
                                </div>
                                <div class="nutrition-value <?= isset($data['winner']) && $data['winner'] === 'b' ? 'winner' : '' ?>">
                                    <?= Html::encode($data['ingredient_b']) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Recommendations -->
    <div class="card mb-4 border-warning">
        <div class="card-header bg-warning">
            <h3 class="mb-0">
                <span class="glyphicon glyphicon-star"></span> Recommendations
            </h3>
        </div>
        <div class="card-body">
            <div style="font-size: 1.1rem; line-height: 1.8; color: #495057;">
                <?= nl2br(Html::encode($model->recommendations)) ?>
            </div>
        </div>
    </div>

    <!-- The Verdict -->
    <div class="card mb-4 border-info">
        <div class="card-header bg-info text-white">
            <h3 class="mb-0">
                <span class="glyphicon glyphicon-check"></span> The Verdict
            </h3>
        </div>
        <div class="card-body">
            <div style="font-size: 1.1rem; line-height: 1.8; color: #495057;">
                <?= nl2br(Html::encode($model->conclusion)) ?>
            </div>
        </div>
    </div>

    <!-- Feedback Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="mb-0">
                <span class="glyphicon glyphicon-comment"></span> Was this comparison helpful?
            </h3>
        </div>
        <div class="card-body text-center py-4">
            <div class="mb-4">
                <?= Html::a(
                    '<span class="glyphicon glyphicon-thumbs-up"></span> Yes, helpful <span class="badge bg-success">' . $model->helpful_count . '</span>',
                    ['helpful', 'id' => $model->id],
                    ['class' => 'btn btn-success btn-lg me-3', 'data-method' => 'post']
                ) ?>
                <?= Html::a(
                    '<span class="glyphicon glyphicon-thumbs-down"></span> Not helpful <span class="badge bg-secondary">' . $model->not_helpful_count . '</span>',
                    ['not-helpful', 'id' => $model->id],
                    ['class' => 'btn btn-outline-secondary btn-lg', 'data-method' => 'post']
                ) ?>
            </div>
            <div class="progress" style="height: 25px; max-width: 400px; margin: 0 auto;">
                <div class="progress-bar bg-success"
                     role="progressbar"
                     style="width: <?= $model->getHelpfulPercentage() ?>%;"
                     aria-valuenow="<?= $model->getHelpfulPercentage() ?>"
                     aria-valuemin="0"
                     aria-valuemax="100">
                    <?= $model->getHelpfulPercentage() ?>% found this helpful
                </div>
            </div>
        </div>
    </div>

    <!-- View Stats & Meta -->
    <div class="text-center text-muted mb-5">
        <p class="mb-2">
            <span class="badge bg-light text-dark">
                <span class="glyphicon glyphicon-eye-open"></span> <?= number_format($model->view_count) ?> views
            </span>
            <?php if ($model->ai_generated): ?>
                <span class="badge bg-info">
                    <span class="glyphicon glyphicon-cog"></span> AI-Generated
                </span>
            <?php endif; ?>
            <span class="badge bg-light text-dark">
                <span class="glyphicon glyphicon-time"></span>
                Updated <?= Yii::$app->formatter->asRelativeTime($model->updated_at) ?>
            </span>
        </p>
    </div>

    <!-- Related Comparisons -->
    <div class="mt-5 mb-4">
        <h3 class="text-center mb-4">Explore More Comparisons</h3>
        <div class="text-center">
            <?= Html::a(
                '<span class="glyphicon glyphicon-th-list"></span> View All Comparisons',
                ['index'],
                ['class' => 'btn btn-primary btn-lg me-2']
            ) ?>
            <?= Html::a(
                '<span class="glyphicon glyphicon-transfer"></span> Create New Comparison',
                ['/ingredient/finder'],
                ['class' => 'btn btn-success btn-lg']
            ) ?>
        </div>
    </div>

</div>

<style>
.me-3 {
    margin-right: 1rem;
}
.me-2 {
    margin-right: 0.5rem;
}
.py-4 {
    padding-top: 1.5rem;
    padding-bottom: 1.5rem;
}
.align-items-center {
    align-items: center;
}
</style>
