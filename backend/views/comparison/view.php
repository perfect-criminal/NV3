<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Comparison;

/* @var $this yii\web\View */
/* @var $model common\models\Comparison */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Comparisons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="comparison-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php if ($model->status != Comparison::STATUS_PUBLISHED): ?>
            <?= Html::a('Publish', ['publish', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
        <?php if ($model->ai_generated): ?>
            <?= Html::a('Regenerate Content', ['regenerate', 'id' => $model->id], [
                'class' => 'btn btn-info',
                'data' => [
                    'confirm' => 'Are you sure you want to regenerate the comparison content? This will overwrite existing content.',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
        <?= Html::a('Archive', ['archive', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this comparison?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Comparison Overview</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                'label' => 'Ingredient A',
                                'value' => $model->ingredientA ? $model->ingredientA->name : 'N/A',
                            ],
                            [
                                'label' => 'Ingredient B',
                                'value' => $model->ingredientB ? $model->ingredientB->name : 'N/A',
                            ],
                            'slug',
                            'title',
                            'winner_category',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Summary</h3>
                </div>
                <div class="panel-body">
                    <?= nl2br(Html::encode($model->summary)) ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Introduction</h3>
                </div>
                <div class="panel-body">
                    <?= nl2br(Html::encode($model->introduction)) ?>
                </div>
            </div>

            <?php if ($model->key_differences): ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Key Differences</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th><?= Html::encode($model->ingredientA ? $model->ingredientA->name : 'Ingredient A') ?></th>
                                    <th><?= Html::encode($model->ingredientB ? $model->ingredientB->name : 'Ingredient B') ?></th>
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
            <?php endif; ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Recommendations</h3>
                </div>
                <div class="panel-body">
                    <?= nl2br(Html::encode($model->recommendations)) ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Conclusion</h3>
                </div>
                <div class="panel-body">
                    <?= nl2br(Html::encode($model->conclusion)) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Status & AI Info</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'status',
                                'value' => Comparison::getStatuses()[$model->status] ?? $model->status,
                            ],
                            'ai_generated:boolean',
                            'ai_model',
                            'generated_at',
                            [
                                'attribute' => 'reviewed_by',
                                'value' => $model->reviewer ? $model->reviewer->username : null,
                            ],
                            'reviewed_at',
                            'published_at',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Engagement Statistics</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'view_count',
                            'helpful_count',
                            'not_helpful_count',
                            [
                                'label' => 'Helpful Percentage',
                                'value' => $model->getHelpfulPercentage() . '%',
                            ],
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">SEO</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'meta_title',
                            'meta_description:ntext',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Timestamps</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'created_at',
                            'updated_at',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

</div>
