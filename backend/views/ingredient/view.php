<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Ingredient;

/* @var $this yii\web\View */
/* @var $model common\models\Ingredient */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Ingredients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ingredient-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php if ($model->status != Ingredient::STATUS_PUBLISHED): ?>
            <?= Html::a('Publish', ['publish', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
        <?php if (!$model->data_verified): ?>
            <?= Html::a('Verify Data', ['verify', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?php endif; ?>
        <?= Html::a('Archive', ['archive', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this ingredient?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Basic Information</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'name',
                            'slug',
                            [
                                'attribute' => 'category',
                                'value' => Ingredient::getCategories()[$model->category] ?? $model->category,
                            ],
                            'subcategory',
                            'scientific_name',
                            'origin',
                            'summary:ntext',
                            'description:ntext',
                            'taste_profile',
                            'texture',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Nutrition Information (per 100g)</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'calories',
                            'protein',
                            'fat',
                            'carbs',
                            'fiber',
                            'sugar',
                            'sodium',
                            'vitamin_b12',
                            'vitamin_d',
                            'iron',
                            'calcium',
                            'zinc',
                            'omega3',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Cooking & Usage</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'storage_tips:ntext',
                            'preparation_tips:ntext',
                            'season',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Sustainability & Cost</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'sustainability_score',
                            'environmental_impact:ntext',
                            'avg_price_per_kg',
                            [
                                'attribute' => 'availability',
                                'value' => Ingredient::getAvailabilityOptions()[$model->availability] ?? $model->availability,
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Status & Verification</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'status',
                                'value' => Ingredient::getStatuses()[$model->status] ?? $model->status,
                            ],
                            'ai_generated:boolean',
                            'data_verified:boolean',
                            [
                                'attribute' => 'verified_by',
                                'value' => $model->verifiedBy->username ?? null,
                            ],
                            'verified_at',
                            'published_at',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Statistics</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'view_count',
                            'comparison_count',
                            'rating',
                            'rating_count',
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
                            'meta_keywords',
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
