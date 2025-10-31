<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Comparison */

$this->title = 'Create Comparison';
$this->params['breadcrumbs'][] = ['label' => 'Comparisons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comparison-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <strong>Tip:</strong> Use the "Generate Comparison" feature for AI-powered comparisons with complete content.
        This manual form is for custom comparisons.
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
