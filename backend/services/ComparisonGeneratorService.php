<?php

namespace backend\services;

use Yii;
use common\models\Comparison;
use common\models\Ingredient;

/**
 * Comparison Generator Service
 * Handles AI-powered comparison generation between ingredients
 */
class ComparisonGeneratorService
{
    /**
     * Generate a new comparison between two ingredients
     *
     * @param Ingredient $ingredientA
     * @param Ingredient $ingredientB
     * @return Comparison|null
     */
    public function generateComparison(Ingredient $ingredientA, Ingredient $ingredientB)
    {
        // Ensure alphabetical order for consistency
        if (strcasecmp($ingredientA->name, $ingredientB->name) > 0) {
            list($ingredientA, $ingredientB) = [$ingredientB, $ingredientA];
        }

        $comparison = new Comparison();
        $comparison->ingredient_a_id = $ingredientA->id;
        $comparison->ingredient_b_id = $ingredientB->id;
        $comparison->title = "{$ingredientA->name} vs {$ingredientB->name}: Complete 2025 Comparison";
        $comparison->slug = "{$ingredientA->slug}-vs-{$ingredientB->slug}";
        $comparison->ai_generated = 1;
        $comparison->ai_model = 'template-based-v1'; // Will be updated when AI integration is added
        $comparison->generated_at = date('Y-m-d H:i:s');
        $comparison->status = Comparison::STATUS_DRAFT;

        // Generate comparison content
        $comparison->summary = $this->generateSummary($ingredientA, $ingredientB);
        $comparison->introduction = $this->generateIntroduction($ingredientA, $ingredientB);
        $comparison->conclusion = $this->generateConclusion($ingredientA, $ingredientB);
        $comparison->key_differences = $this->generateKeyDifferences($ingredientA, $ingredientB);
        $comparison->recommendations = $this->generateRecommendations($ingredientA, $ingredientB);
        $comparison->winner_category = $this->determineWinnerCategory($ingredientA, $ingredientB);
        $comparison->comparison_data = $this->generateComparisonData($ingredientA, $ingredientB);

        // Generate SEO data
        $comparison->meta_title = $comparison->title;
        $comparison->meta_description = substr($comparison->summary, 0, 155);
        $comparison->schema_data = $this->generateSchemaData($comparison, $ingredientA, $ingredientB);

        if ($comparison->save()) {
            // Increment comparison count for both ingredients
            $ingredientA->comparison_count++;
            $ingredientA->save(false);

            $ingredientB->comparison_count++;
            $ingredientB->save(false);

            return $comparison;
        }

        return null;
    }

    /**
     * Regenerate content for existing comparison
     *
     * @param Comparison $comparison
     * @return bool
     */
    public function regenerateComparison(Comparison $comparison)
    {
        $ingredientA = $comparison->ingredientA;
        $ingredientB = $comparison->ingredientB;

        if (!$ingredientA || !$ingredientB) {
            return false;
        }

        $comparison->summary = $this->generateSummary($ingredientA, $ingredientB);
        $comparison->introduction = $this->generateIntroduction($ingredientA, $ingredientB);
        $comparison->conclusion = $this->generateConclusion($ingredientA, $ingredientB);
        $comparison->key_differences = $this->generateKeyDifferences($ingredientA, $ingredientB);
        $comparison->recommendations = $this->generateRecommendations($ingredientA, $ingredientB);
        $comparison->winner_category = $this->determineWinnerCategory($ingredientA, $ingredientB);
        $comparison->comparison_data = $this->generateComparisonData($ingredientA, $ingredientB);
        $comparison->generated_at = date('Y-m-d H:i:s');

        return $comparison->save();
    }

    /**
     * Generate summary
     */
    protected function generateSummary(Ingredient $a, Ingredient $b)
    {
        $proteinWinner = $this->compareNutrient('protein', $a, $b);
        $calorieWinner = $this->compareNutrient('calories', $a, $b, true); // Lower is better

        return "Comparing {$a->name} and {$b->name}: {$proteinWinner['winner']->name} has {$proteinWinner['winner']->protein}g protein per 100g compared to {$proteinWinner['loser']->protein}g in {$proteinWinner['loser']->name}. For calories, {$calorieWinner['winner']->name} is lower with {$calorieWinner['winner']->calories} calories versus {$calorieWinner['loser']->calories}. Both are excellent vegan options with unique nutritional profiles and culinary uses.";
    }

    /**
     * Generate introduction
     */
    protected function generateIntroduction(Ingredient $a, Ingredient $b)
    {
        return <<<EOT
When choosing between {$a->name} and {$b->name}, understanding their nutritional differences, taste profiles, and best uses can help you make an informed decision for your plant-based diet.

{$a->name} is a {$a->category} known for its {$a->taste_profile}, while {$b->name} is a {$b->category} with {$b->taste_profile}. Both are popular in vegan cooking, but they serve different purposes and offer unique nutritional benefits.

In this comprehensive comparison, we'll examine their nutritional content, taste and texture, best culinary uses, cost, sustainability, and help you determine which is best for your specific needs.
EOT;
    }

    /**
     * Generate conclusion
     */
    protected function generateConclusion(Ingredient $a, Ingredient $b)
    {
        $proteinWinner = $this->compareNutrient('protein', $a, $b);

        return <<<EOT
**The Verdict:**

Both {$a->name} and {$b->name} are valuable additions to a vegan diet, each with distinct advantages.

**Choose {$proteinWinner['winner']->name} if you:**
- Need higher protein content
- Prefer {$proteinWinner['winner']->texture} texture
- Are looking for {$proteinWinner['winner']->taste_profile}

**Choose {$proteinWinner['loser']->name} if you:**
- Want variety in your diet
- Prefer {$proteinWinner['loser']->texture} texture
- Enjoy {$proteinWinner['loser']->taste_profile}

Ultimately, there's no wrong choice. Both ingredients can be part of a healthy, balanced vegan lifestyle. Consider keeping both in your pantry for maximum versatility.
EOT;
    }

    /**
     * Generate key differences
     */
    protected function generateKeyDifferences(Ingredient $a, Ingredient $b)
    {
        return [
            [
                'category' => 'Protein Content',
                'ingredient_a' => $a->protein ? round($a->protein, 1) . 'g per 100g' : 'Not available',
                'ingredient_b' => $b->protein ? round($b->protein, 1) . 'g per 100g' : 'Not available',
            ],
            [
                'category' => 'Calories',
                'ingredient_a' => $a->calories ? round($a->calories) . ' cal per 100g' : 'Not available',
                'ingredient_b' => $b->calories ? round($b->calories) . ' cal per 100g' : 'Not available',
            ],
            [
                'category' => 'Taste Profile',
                'ingredient_a' => $a->taste_profile ?: 'Varies',
                'ingredient_b' => $b->taste_profile ?: 'Varies',
            ],
            [
                'category' => 'Texture',
                'ingredient_a' => $a->texture ?: 'Varies',
                'ingredient_b' => $b->texture ?: 'Varies',
            ],
            [
                'category' => 'Best Uses',
                'ingredient_a' => is_array($a->common_uses) ? implode(', ', array_slice($a->common_uses, 0, 3)) : 'Versatile',
                'ingredient_b' => is_array($b->common_uses) ? implode(', ', array_slice($b->common_uses, 0, 3)) : 'Versatile',
            ],
        ];
    }

    /**
     * Generate recommendations
     */
    protected function generateRecommendations(Ingredient $a, Ingredient $b)
    {
        return <<<EOT
**For Beginners:** Start with the ingredient that matches your current taste preferences. If you're new to vegan cooking, {$a->name} might be easier to work with due to its versatility.

**For Athletes:** If protein is your priority, choose the ingredient with higher protein content to support muscle recovery and growth.

**For Weight Management:** Consider the calorie content and choose the option that better fits your daily caloric goals.

**For Variety:** Don't choose! Both {$a->name} and {$b->name} can coexist in your meal rotation, providing nutritional variety and preventing taste fatigue.
EOT;
    }

    /**
     * Determine winner category
     */
    protected function determineWinnerCategory(Ingredient $a, Ingredient $b)
    {
        $proteinWinner = $this->compareNutrient('protein', $a, $b);
        return $proteinWinner['winner']->name . ' for protein';
    }

    /**
     * Generate comparison data
     */
    protected function generateComparisonData(Ingredient $a, Ingredient $b)
    {
        return [
            'nutrition' => [
                'protein' => [
                    'ingredient_a' => $a->protein,
                    'ingredient_b' => $b->protein,
                    'winner' => $a->protein > $b->protein ? 'a' : 'b',
                ],
                'calories' => [
                    'ingredient_a' => $a->calories,
                    'ingredient_b' => $b->calories,
                    'winner' => $a->calories < $b->calories ? 'a' : 'b', // Lower is better
                ],
                'fat' => [
                    'ingredient_a' => $a->fat,
                    'ingredient_b' => $b->fat,
                ],
                'carbs' => [
                    'ingredient_a' => $a->carbs,
                    'ingredient_b' => $b->carbs,
                ],
                'fiber' => [
                    'ingredient_a' => $a->fiber,
                    'ingredient_b' => $b->fiber,
                    'winner' => $a->fiber > $b->fiber ? 'a' : 'b',
                ],
            ],
            'sustainability' => [
                'score_a' => $a->sustainability_score,
                'score_b' => $b->sustainability_score,
                'winner' => $a->sustainability_score > $b->sustainability_score ? 'a' : 'b',
            ],
            'cost' => [
                'price_a' => $a->avg_price_per_kg,
                'price_b' => $b->avg_price_per_kg,
                'winner' => $a->avg_price_per_kg < $b->avg_price_per_kg ? 'a' : 'b', // Lower is better
            ],
        ];
    }

    /**
     * Generate Schema.org data
     */
    protected function generateSchemaData(Comparison $comparison, Ingredient $a, Ingredient $b)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $comparison->title,
            'description' => $comparison->summary,
            'datePublished' => $comparison->created_at,
            'dateModified' => $comparison->updated_at,
            'about' => [
                [
                    '@type' => 'Thing',
                    'name' => $a->name,
                ],
                [
                    '@type' => 'Thing',
                    'name' => $b->name,
                ],
            ],
        ];
    }

    /**
     * Compare nutrient between two ingredients
     */
    protected function compareNutrient($nutrient, Ingredient $a, Ingredient $b, $lowerIsBetter = false)
    {
        $valueA = $a->$nutrient ?? 0;
        $valueB = $b->$nutrient ?? 0;

        if ($lowerIsBetter) {
            $winner = $valueA < $valueB ? $a : $b;
            $loser = $valueA < $valueB ? $b : $a;
        } else {
            $winner = $valueA > $valueB ? $a : $b;
            $loser = $valueA > $valueB ? $b : $a;
        }

        return [
            'winner' => $winner,
            'loser' => $loser,
            'value_a' => $valueA,
            'value_b' => $valueB,
        ];
    }
}
