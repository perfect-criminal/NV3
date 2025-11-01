<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model common\models\Ingredient */
?>

<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="card ingredient-card h-100">
        <!-- Image Section -->
        <?php if ($model->featuredImage): ?>
            <div class="position-relative">
                <img src="<?= Html::encode($model->featuredImage->getUrl()) ?>"
                     alt="<?= Html::encode($model->name) ?>"
                     class="card-img-top"
                     style="height: 200px; object-fit: cover;">
                <span class="badge badge-category category-badge position-absolute">
                    <?= Html::encode(common\models\Ingredient::getCategories()[$model->category] ?? $model->category) ?>
                </span>
            </div>
        <?php else: ?>
            <div class="position-relative" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 200px; display: flex; align-items: center; justify-content: center;">
                <h1 style="color: white; font-size: 4rem; margin: 0; font-weight: 700;">
                    <?= Html::encode(mb_strtoupper(mb_substr($model->name, 0, 1))) ?>
                </h1>
                <span class="badge badge-category category-badge position-absolute">
                    <?= Html::encode(common\models\Ingredient::getCategories()[$model->category] ?? $model->category) ?>
                </span>
            </div>
        <?php endif; ?>

        <!-- Card Body -->
        <div class="card-body d-flex flex-column">
            <h5 class="card-title mb-3">
                <?= Html::a(
                    Html::encode($model->name),
                    ['view', 'slug' => $model->slug],
                    ['class' => 'text-dark text-decoration-none']
                ) ?>
            </h5>

            <?php if ($model->summary): ?>
                <p class="card-text text-muted small mb-3" style="min-height: 60px;">
                    <?= Html::encode(mb_substr($model->summary, 0, 100)) ?><?= mb_strlen($model->summary) > 100 ? '...' : '' ?>
                </p>
            <?php else: ?>
                <p class="card-text text-muted small mb-3" style="min-height: 60px;">
                    Explore detailed nutrition information, cooking tips, and more about <?= Html::encode($model->name) ?>.
                </p>
            <?php endif; ?>

            <!-- Nutrition Info -->
            <div class="nutrition-info mt-auto">
                <div class="nutrition-item">
                    <span class="value"><?= $model->protein ? round($model->protein, 1) : '0' ?>g</span>
                    <span class="label">Protein</span>
                </div>
                <div class="nutrition-item">
                    <span class="value"><?= $model->calories ? round($model->calories) : '0' ?></span>
                    <span class="label">Calories</span>
                </div>
                <div class="nutrition-item">
                    <span class="value"><?= $model->fiber ? round($model->fiber, 1) : '0' ?>g</span>
                    <span class="label">Fiber</span>
                </div>
            </div>
        </div>

        <!-- Card Footer -->
        <div class="card-footer bg-white border-top">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="text-muted">
                    <span class="glyphicon glyphicon-eye-open"></span> <?= number_format($model->view_count) ?>
                </small>
                <?php if ($model->rating): ?>
                    <small class="text-warning">
                        <span class="glyphicon glyphicon-star"></span> <?= round($model->rating, 1) ?>/5
                    </small>
                <?php endif; ?>
            </div>
            <?= Html::a(
                'View Details <span class="glyphicon glyphicon-arrow-right"></span>',
                ['view', 'slug' => $model->slug],
                ['class' => 'btn btn-primary btn-sm w-100']
            ) ?>
        </div>
    </div>
</div>
