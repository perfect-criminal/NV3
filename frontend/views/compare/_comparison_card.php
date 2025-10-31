<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model common\models\Comparison */
?>

<div class="col-md-4 col-sm-6">
    <div class="panel panel-default comparison-card" style="min-height: 250px;">
        <div class="panel-body">
            <h4>
                <?= Html::a(Html::encode($model->title), ['view', 'slug' => $model->slug]) ?>
            </h4>

            <div class="comparison-ingredients" style="margin: 15px 0;">
                <span class="label label-primary"><?= Html::encode($model->ingredientA->name ?? 'N/A') ?></span>
                <strong>VS</strong>
                <span class="label label-primary"><?= Html::encode($model->ingredientB->name ?? 'N/A') ?></span>
            </div>

            <p class="text-muted">
                <?= Html::encode(mb_substr($model->summary, 0, 120)) ?>...
            </p>

            <div class="comparison-meta">
                <small class="text-muted">
                    <span class="glyphicon glyphicon-eye-open"></span> <?= number_format($model->view_count) ?> views
                    &nbsp;&nbsp;
                    <span class="glyphicon glyphicon-thumbs-up"></span> <?= $model->getHelpfulPercentage() ?>% helpful
                </small>
            </div>
        </div>
        <div class="panel-footer">
            <?= Html::a('Read Comparison', ['view', 'slug' => $model->slug], ['class' => 'btn btn-primary btn-block']) ?>
        </div>
    </div>
</div>
