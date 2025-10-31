<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'NV3 Vegan Database - Your Factual Guide to Plant-Based Ingredients';
?>
<div class="site-index">

    <!-- Hero Section -->
    <div class="jumbotron text-center bg-light">
        <h1 class="display-4">
            <span class="glyphicon glyphicon-leaf"></span>
            Welcome to NV3 Vegan Database
        </h1>
        <p class="lead">
            Your comprehensive, factual resource for vegan ingredients and comparisons.
            <br>Make informed choices with science-backed nutritional data.
        </p>
        <p>
            <?= Html::a('Browse Ingredients', ['/ingredient/index'], ['class' => 'btn btn-success btn-lg']) ?>
            <?= Html::a('Compare Now', ['/ingredient/finder'], ['class' => 'btn btn-primary btn-lg']) ?>
        </p>
    </div>

    <!-- Feature Cards -->
    <div class="body-content">
        <div class="row">

            <!-- Comprehensive Database -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h2>
                            <span class="glyphicon glyphicon-th-large text-success"></span>
                        </h2>
                        <h3 class="card-title">Comprehensive Database</h3>
                        <p class="card-text">
                            Explore our extensive collection of vegan ingredients with detailed nutritional information,
                            cooking tips, sustainability scores, and more.
                        </p>
                        <p>
                            <?= Html::a('Browse by Category', ['/ingredient/index'], ['class' => 'btn btn-outline-success']) ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Smart Comparisons -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h2>
                            <span class="glyphicon glyphicon-transfer text-primary"></span>
                        </h2>
                        <h3 class="card-title">Smart Comparisons</h3>
                        <p class="card-text">
                            Compare any two ingredients side-by-side. Get detailed analysis of nutrition,
                            taste, sustainability, and practical usage recommendations.
                        </p>
                        <p>
                            <?= Html::a('Start Comparing', ['/ingredient/finder'], ['class' => 'btn btn-outline-primary']) ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Advanced Search -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h2>
                            <span class="glyphicon glyphicon-search text-info"></span>
                        </h2>
                        <h3 class="card-title">Advanced Search</h3>
                        <p class="card-text">
                            Filter ingredients by protein content, calories, dietary preferences, sustainability,
                            and more. Find exactly what you need.
                        </p>
                        <p>
                            <?= Html::a('Advanced Search', ['/ingredient/search'], ['class' => 'btn btn-outline-info']) ?>
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Quick Links Section -->
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading bg-light">
                        <h3 class="text-center">Popular Categories</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <?= Html::a(
                                    '<span class="glyphicon glyphicon-grain"></span><br><strong>Proteins</strong>',
                                    ['/ingredient/category', 'category' => 'protein'],
                                    ['class' => 'btn btn-lg btn-outline-success btn-block']
                                ) ?>
                            </div>
                            <div class="col-md-3 mb-3">
                                <?= Html::a(
                                    '<span class="glyphicon glyphicon-grain"></span><br><strong>Grains</strong>',
                                    ['/ingredient/category', 'category' => 'grain'],
                                    ['class' => 'btn btn-lg btn-outline-success btn-block']
                                ) ?>
                            </div>
                            <div class="col-md-3 mb-3">
                                <?= Html::a(
                                    '<span class="glyphicon glyphicon-apple"></span><br><strong>Fruits</strong>',
                                    ['/ingredient/category', 'category' => 'fruit'],
                                    ['class' => 'btn btn-lg btn-outline-success btn-block']
                                ) ?>
                            </div>
                            <div class="col-md-3 mb-3">
                                <?= Html::a(
                                    '<span class="glyphicon glyphicon-glass"></span><br><strong>Milk Alternatives</strong>',
                                    ['/ingredient/category', 'category' => 'milk_alternative'],
                                    ['class' => 'btn btn-lg btn-outline-success btn-block']
                                ) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Why Choose Us -->
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <h3 class="text-center">
                        <span class="glyphicon glyphicon-info-sign"></span>
                        Why NV3 Vegan Database?
                    </h3>
                    <div class="row mt-4">
                        <div class="col-md-3 text-center">
                            <p><strong>Factual Data</strong></p>
                            <p class="small">Science-backed nutritional information from reliable sources</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <p><strong>Comprehensive</strong></p>
                            <p class="small">90+ data points per ingredient including nutrition, sustainability & more</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <p><strong>Smart Comparisons</strong></p>
                            <p class="small">AI-powered detailed comparisons to help you make informed choices</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <p><strong>Always Updated</strong></p>
                            <p class="small">Regular updates with new ingredients and latest research</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="row mt-5 mb-5">
            <div class="col-md-12 text-center">
                <h3>Ready to explore?</h3>
                <p class="lead">Start by browsing our ingredient database or compare your favorite plant-based foods</p>
                <p>
                    <?= Html::a('Browse All Ingredients', ['/ingredient/index'], ['class' => 'btn btn-success btn-lg mr-2']) ?>
                    <?= Html::a('Find a Comparison', ['/ingredient/finder'], ['class' => 'btn btn-primary btn-lg']) ?>
                </p>
            </div>
        </div>

    </div>
</div>
