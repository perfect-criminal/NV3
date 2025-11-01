<?php

/** @var yii\web\View $this */
/** @var array $stats */
/** @var common\models\Ingredient[] $recentIngredients */
/** @var common\models\Comparison[] $recentComparisons */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Admin Dashboard';
?>
<div class="site-index">

    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h1 class="mb-2">
                        <span class="glyphicon glyphicon-dashboard"></span>
                        Welcome to NV3 Admin Dashboard
                    </h1>
                    <p class="lead mb-0">
                        Manage your vegan ingredient database and comparisons
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">

        <!-- Ingredients Stats -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2">TOTAL INGREDIENTS</h6>
                            <h2 class="mb-0 text-success"><?= $stats['totalIngredients'] ?></h2>
                        </div>
                        <div class="text-success">
                            <span class="glyphicon glyphicon-leaf" style="font-size: 2rem;"></span>
                        </div>
                    </div>
                    <hr>
                    <small class="text-muted">
                        <span class="text-success"><?= $stats['publishedIngredients'] ?></span> Published •
                        <span class="text-warning"><?= $stats['draftIngredients'] ?></span> Drafts
                    </small>
                    <div class="mt-3">
                        <?= Html::a('View All', ['/ingredient/index'], ['class' => 'btn btn-sm btn-outline-success']) ?>
                        <?= Html::a('Add New', ['/ingredient/create'], ['class' => 'btn btn-sm btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparisons Stats -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2">TOTAL COMPARISONS</h6>
                            <h2 class="mb-0 text-primary"><?= $stats['totalComparisons'] ?></h2>
                        </div>
                        <div class="text-primary">
                            <span class="glyphicon glyphicon-transfer" style="font-size: 2rem;"></span>
                        </div>
                    </div>
                    <hr>
                    <small class="text-muted">
                        <span class="text-primary"><?= $stats['publishedComparisons'] ?></span> Published •
                        <span class="text-info"><?= $stats['aiGeneratedComparisons'] ?></span> AI Generated
                    </small>
                    <div class="mt-3">
                        <?= Html::a('View All', ['/comparison/index'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                        <?= Html::a('Generate', ['/comparison/generate'], ['class' => 'btn btn-sm btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4 col-md-12 mb-3">
            <div class="card border-info h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-3">
                        <span class="glyphicon glyphicon-flash"></span>
                        QUICK ACTIONS
                    </h6>
                    <div class="d-grid gap-2">
                        <?= Html::a(
                            '<span class="glyphicon glyphicon-plus"></span> Add Ingredient',
                            ['/ingredient/create'],
                            ['class' => 'btn btn-success btn-sm mb-2']
                        ) ?>
                        <?= Html::a(
                            '<span class="glyphicon glyphicon-flash"></span> Generate Comparison',
                            ['/comparison/generate'],
                            ['class' => 'btn btn-primary btn-sm mb-2']
                        ) ?>
                        <?= Html::a(
                            '<span class="glyphicon glyphicon-list-alt"></span> Batch Generate',
                            ['/comparison/batch-generate'],
                            ['class' => 'btn btn-info btn-sm mb-2']
                        ) ?>
                        <?= Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span> View Frontend',
                            'http://localhost:8081',
                            ['class' => 'btn btn-outline-secondary btn-sm', 'target' => '_blank']
                        ) ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Recent Activity -->
    <div class="row">

        <!-- Recent Ingredients -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <span class="glyphicon glyphicon-leaf text-success"></span>
                        Recent Ingredients
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($recentIngredients)): ?>
                        <div class="p-4 text-center text-muted">
                            <p class="mb-3">
                                <span class="glyphicon glyphicon-info-sign" style="font-size: 3rem;"></span>
                            </p>
                            <p>No ingredients yet. Start by adding your first ingredient!</p>
                            <p>
                                <?= Html::a('Add Ingredient', ['/ingredient/create'], ['class' => 'btn btn-success']) ?>
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recentIngredients as $ingredient): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">
                                                <?= Html::a(Html::encode($ingredient->name), ['/ingredient/view', 'id' => $ingredient->id]) ?>
                                            </h6>
                                            <small class="text-muted">
                                                <span class="badge bg-secondary"><?= ucfirst($ingredient->category) ?></span>
                                                <?php if ($ingredient->status === 'published'): ?>
                                                    <span class="badge bg-success">Published</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Draft</span>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                        <small class="text-muted">
                                            <?= Yii::$app->formatter->asRelativeTime($ingredient->created_at) ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="card-footer bg-light text-center">
                            <?= Html::a('View All Ingredients', ['/ingredient/index'], ['class' => 'btn btn-sm btn-outline-success']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Comparisons -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <span class="glyphicon glyphicon-transfer text-primary"></span>
                        Recent Comparisons
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($recentComparisons)): ?>
                        <div class="p-4 text-center text-muted">
                            <p class="mb-3">
                                <span class="glyphicon glyphicon-info-sign" style="font-size: 3rem;"></span>
                            </p>
                            <p>No comparisons yet. Generate your first comparison!</p>
                            <p>
                                <?= Html::a('Generate Comparison', ['/comparison/generate'], ['class' => 'btn btn-primary']) ?>
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recentComparisons as $comparison): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">
                                                <?= Html::a(Html::encode($comparison->title), ['/comparison/view', 'id' => $comparison->id]) ?>
                                            </h6>
                                            <small class="text-muted">
                                                <?php if ($comparison->ai_generated): ?>
                                                    <span class="badge bg-info">AI Generated</span>
                                                <?php endif; ?>
                                                <?php if ($comparison->status === 'published'): ?>
                                                    <span class="badge bg-success">Published</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Draft</span>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                        <small class="text-muted">
                                            <?= Yii::$app->formatter->asRelativeTime($comparison->created_at) ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="card-footer bg-light text-center">
                            <?= Html::a('View All Comparisons', ['/comparison/index'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <!-- Help & Resources -->
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <h5>
                    <span class="glyphicon glyphicon-question-sign"></span>
                    Getting Started
                </h5>
                <p class="mb-2">
                    <strong>New to NV3?</strong> Here's how to get started:
                </p>
                <ol class="mb-0">
                    <li>Add ingredients to your database with detailed nutritional information</li>
                    <li>Generate AI-powered comparisons between ingredients</li>
                    <li>Publish content to make it visible on the frontend</li>
                    <li>Use the Advanced Search to help users find the perfect ingredients</li>
                </ol>
            </div>
        </div>
    </div>

</div>

<style>
.card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.d-grid {
    display: grid;
}
.gap-2 {
    gap: 0.5rem;
}
</style>
