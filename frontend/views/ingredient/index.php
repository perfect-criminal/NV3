<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use common\models\Ingredient;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\IngredientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Browse Vegan Ingredients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ingredient-index">

    <!-- Hero Section -->
    <div class="jumbotron text-center mb-5">
        <h1 class="display-4">
            <span class="glyphicon glyphicon-leaf"></span>
            Vegan Ingredient Database
        </h1>
        <p class="lead mb-4">
            Explore our comprehensive database of plant-based ingredients with detailed nutrition information,
            cooking tips, and sustainability data.
        </p>
        <div>
            <?= Html::a(
                '<span class="glyphicon glyphicon-search"></span> Advanced Search',
                ['search'],
                ['class' => 'btn btn-light btn-lg']
            ) ?>
            <?= Html::a(
                '<span class="glyphicon glyphicon-transfer"></span> Compare Ingredients',
                ['finder'],
                ['class' => 'btn btn-success btn-lg']
            ) ?>
        </div>
    </div>

    <!-- Quick Search and Filters -->
    <div class="search-form mb-5">
        <h3 class="mb-4">
            <span class="glyphicon glyphicon-search"></span> Quick Search
        </h3>

        <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['index']]); ?>

        <div class="row">
            <div class="col-md-5">
                <?= $form->field($searchModel, 'name')->textInput([
                    'placeholder' => 'Search by name...',
                    'class' => 'form-control form-control-lg'
                ])->label('Ingredient Name') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($searchModel, 'category')->dropDownList(
                    Ingredient::getCategories(),
                    ['prompt' => 'All Categories', 'class' => 'form-control form-control-lg']
                )->label('Category') ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($searchModel, 'availability')->dropDownList(
                    Ingredient::getAvailabilityOptions(),
                    ['prompt' => 'All Availability', 'class' => 'form-control form-control-lg']
                )->label('Availability') ?>
            </div>
        </div>

        <div class="d-flex gap-2">
            <?= Html::submitButton(
                '<span class="glyphicon glyphicon-search"></span> Search',
                ['class' => 'btn btn-primary btn-lg']
            ) ?>
            <?= Html::a(
                '<span class="glyphicon glyphicon-filter"></span> Advanced Search',
                ['search'],
                ['class' => 'btn btn-info btn-lg']
            ) ?>
            <?= Html::a(
                '<span class="glyphicon glyphicon-refresh"></span> Reset',
                ['index'],
                ['class' => 'btn btn-outline-secondary btn-lg']
            ) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <!-- Browse by Category -->
    <div class="mb-5">
        <h3 class="mb-4">
            <span class="glyphicon glyphicon-th"></span> Browse by Category
        </h3>

        <div class="category-grid">
            <?php foreach (Ingredient::getCategories() as $key => $label): ?>
                <div class="category-card">
                    <div class="icon">
                        <?php
                        // Icon mapping for different categories
                        $icons = [
                            'protein' => 'glyphicon-grain',
                            'grain' => 'glyphicon-grain',
                            'vegetable' => 'glyphicon-leaf',
                            'fruit' => 'glyphicon-apple',
                            'nut' => 'glyphicon-certificate',
                            'seed' => 'glyphicon-th',
                            'legume' => 'glyphicon-th-large',
                            'milk_alternative' => 'glyphicon-glass',
                            'other' => 'glyphicon-star',
                        ];
                        $icon = $icons[$key] ?? 'glyphicon-star';
                        ?>
                        <span class="glyphicon <?= $icon ?>"></span>
                    </div>
                    <h3><?= Html::encode($label) ?></h3>
                    <p class="count">
                        <?= Html::a(
                            'Explore Category <span class="glyphicon glyphicon-arrow-right"></span>',
                            ['category', 'category' => $key],
                            ['class' => 'btn btn-outline-primary btn-sm mt-2']
                        ) ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Results Section -->
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <span class="glyphicon glyphicon-list"></span>
                All Ingredients
            </h2>
            <p class="text-muted mb-0">
                <?= $dataProvider->getTotalCount() ?> ingredients found
            </p>
        </div>

        <?php if ($dataProvider->getTotalCount() > 0): ?>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_ingredient_card',
                'layout' => '<div class="row">{items}</div><div class="mt-4 d-flex justify-content-center">{pager}</div>',
                'pager' => [
                    'class' => 'yii\bootstrap5\LinkPager',
                    'options' => ['class' => 'pagination'],
                ],
            ]); ?>
        <?php else: ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="icon">
                    <span class="glyphicon glyphicon-info-sign"></span>
                </div>
                <h3>No ingredients found</h3>
                <p>Try adjusting your search criteria or browse by category to discover ingredients.</p>
                <?= Html::a(
                    '<span class="glyphicon glyphicon-refresh"></span> Reset Search',
                    ['index'],
                    ['class' => 'btn btn-primary btn-lg']
                ) ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<style>
.d-flex {
    display: flex;
}
.gap-2 {
    gap: 0.5rem;
}
.justify-content-between {
    justify-content: space-between;
}
.justify-content-center {
    justify-content: center;
}
.align-items-center {
    align-items: center;
}
.w-100 {
    width: 100%;
}
.position-relative {
    position: relative;
}
.position-absolute {
    position: absolute;
}
.flex-column {
    flex-direction: column;
}
.mt-auto {
    margin-top: auto;
}
</style>
