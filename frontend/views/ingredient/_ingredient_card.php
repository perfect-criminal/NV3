<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model common\models\Ingredient */
?>

<div class="col-md-3 col-sm-6">
    <div class="panel panel-default ingredient-card" style="min-height: 350px;">
        <?php if ($model->featuredImage): ?>
            <div class="panel-heading" style="padding: 0;">
                <img src="<?= Html::encode($model->featuredImage->getUrl()) ?>"
                     alt="<?= Html::encode($model->name) ?>"
                     class="img-responsive"
                     style="width: 100%; height: 150px; object-fit: cover;">
            </div>
        <?php else: ?>
            <div class="panel-heading" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 150px; display: flex; align-items: center; justify-content: center;">
                <h2 style="color: white; margin: 0;"><?= Html::encode(substr($model->name, 0, 1)) ?></h2>
            </div>
        <?php endif; ?>

        <div class="panel-body">
            <h4 style="min-height: 50px;">
                <?= Html::a(Html::encode($model->name), ['view', 'slug' => $model->slug]) ?>
            </h4>

            <p class="text-muted">
                <span class="label label-primary"><?= Html::encode(common\models\Ingredient::getCategories()[$model->category] ?? $model->category) ?></span>
            </p>

            <?php if ($model->summary): ?>
                <p class="text-muted" style="font-size: 13px;">
                    <?= Html::encode(mb_substr($model->summary, 0, 80)) ?>...
                </p>
            <?php endif; ?>

            <div class="ingredient-nutrition" style="margin: 10px 0;">
                <?php if ($model->protein): ?>
                    <small><strong>Protein:</strong> <?= round($model->protein, 1) ?>g</small><br>
                <?php endif; ?>
                <?php if ($model->calories): ?>
                    <small><strong>Calories:</strong> <?= round($model->calories) ?></small><br>
                <?php endif; ?>
                <?php if ($model->fiber): ?>
                    <small><strong>Fiber:</strong> <?= round($model->fiber, 1) ?>g</small>
                <?php endif; ?>
            </div>

            <div class="ingredient-meta">
                <small class="text-muted">
                    <span class="glyphicon glyphicon-eye-open"></span> <?= number_format($model->view_count) ?> views
                    <?php if ($model->rating): ?>
                        &nbsp;&nbsp;<span class="glyphicon glyphicon-star"></span> <?= round($model->rating, 1) ?>/5
                    <?php endif; ?>
                </small>
            </div>
        </div>
        <div class="panel-footer">
            <?= Html::a('View Details', ['view', 'slug' => $model->slug], ['class' => 'btn btn-primary btn-block']) ?>
        </div>
    </div>
</div>
