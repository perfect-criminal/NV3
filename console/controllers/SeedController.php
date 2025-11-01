<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use common\models\Ingredient;
use common\models\Comparison;
use backend\services\ComparisonGeneratorService;

/**
 * Data seeder for development and testing
 */
class SeedController extends Controller
{
    /**
     * Seeds the database with sample ingredients
     */
    public function actionIngredients()
    {
        $this->stdout("Seeding sample ingredients...\n", \yii\helpers\Console::FG_YELLOW);

        $ingredients = [
            [
                'name' => 'Tofu',
                'slug' => 'tofu',
                'category' => 'protein',
                'summary' => 'A versatile plant-based protein made from soybeans, perfect for stir-fries, scrambles, and grilling.',
                'description' => 'Tofu, also known as bean curd, is made by coagulating soy milk and pressing the resulting curds into soft white blocks. It has been a staple in Asian cuisines for over 2,000 years and is valued for its high protein content and ability to absorb flavors.',
                'calories' => 76,
                'protein' => 8.0,
                'fat' => 4.8,
                'carbs' => 1.9,
                'fiber' => 0.3,
                'iron' => 1.4,
                'calcium' => 350,
                'vitamin_d' => 0,
                'vitamin_b12' => 0,
                'omega3' => 0.4,
                'availability' => 'common',
                'season' => 'all year',
                'storage_tips' => 'Store in water in the refrigerator, changing water daily. Use within 3-5 days of opening.',
                'cooking_methods' => json_encode(['stir-fry', 'bake', 'grill', 'scramble', 'pan-fry']),
                'common_uses' => json_encode(['stir-fries', 'scrambles', 'curries', 'soups', 'sandwiches']),
                'health_benefits' => json_encode([
                    'High in protein',
                    'Contains all 9 essential amino acids',
                    'Good source of iron and calcium',
                    'May reduce risk of heart disease',
                    'Low in calories'
                ]),
                'dietary_flags' => json_encode(['gluten-free', 'nut-free']),
                'allergens' => json_encode(['soy']),
                'sustainability_score' => 8,
                'environmental_impact' => 'Low carbon footprint (2.0 kg CO2e/kg), moderate water usage',
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Quinoa',
                'slug' => 'quinoa',
                'category' => 'grain',
                'summary' => 'A complete protein grain from South America, fluffy when cooked with a slightly nutty flavor.',
                'description' => 'Quinoa is a pseudocereal that has been cultivated in the Andean region for thousands of years. It contains all nine essential amino acids, making it a complete protein source, which is rare among plant foods.',
                'calories' => 120,
                'protein' => 4.4,
                'fat' => 1.9,
                'carbs' => 21.3,
                'fiber' => 2.8,
                'iron' => 1.5,
                'calcium' => 17,
                'zinc' => 1.1,
                'nutrition_extended' => json_encode([
                    'magnesium' => 64,
                    'phosphorus' => 152,
                    'manganese' => 0.6
                ]),
                'availability' => 'common',
                'season' => 'all year',
                'storage_tips' => 'Store dry quinoa in an airtight container in a cool, dry place for up to 1 year.',
                'cooking_methods' => json_encode(['boil', 'steam', 'pressure-cook']),
                'common_uses' => json_encode(['salads', 'bowls', 'side-dishes', 'breakfast-porridge', 'stuffing']),
                'health_benefits' => json_encode([
                    'Complete protein source',
                    'High in fiber',
                    'Rich in minerals',
                    'Gluten-free',
                    'May help control blood sugar'
                ]),
                'dietary_flags' => json_encode(['gluten-free', 'nut-free', 'soy-free']),
                'sustainability_score' => 7,
                'environmental_impact' => 'Medium carbon footprint (1.5 kg CO2e/kg), medium water usage',
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Chickpeas',
                'slug' => 'chickpeas',
                'category' => 'legume',
                'summary' => 'Versatile legumes rich in protein and fiber, perfect for hummus, curries, and roasting.',
                'description' => 'Chickpeas, also known as garbanzo beans, are one of the earliest cultivated legumes. They have a nutty taste and are incredibly versatile, forming the base of many Middle Eastern and Mediterranean dishes.',
                'calories' => 164,
                'protein' => 8.9,
                'fat' => 2.6,
                'carbs' => 27.4,
                'fiber' => 7.6,
                'iron' => 2.9,
                'calcium' => 49,
                'nutrition_extended' => json_encode([
                    'folate' => 172,
                    'vitamin_b6' => 0.5,
                    'potassium' => 291
                ]),
                'availability' => 'common',
                'season' => 'all year',
                'storage_tips' => 'Dried chickpeas can be stored for up to 1 year. Cooked chickpeas keep for 3-4 days in the fridge.',
                'cooking_methods' => json_encode(['boil', 'roast', 'pressure-cook', 'blend']),
                'common_uses' => json_encode(['hummus', 'curries', 'salads', 'roasted-snacks', 'falafel']),
                'health_benefits' => json_encode([
                    'High in protein and fiber',
                    'May help weight management',
                    'Supports digestive health',
                    'Good source of folate',
                    'May improve blood sugar control'
                ]),
                'dietary_flags' => json_encode(['gluten-free', 'nut-free', 'soy-free']),
                'sustainability_score' => 9,
                'environmental_impact' => 'Very low carbon footprint (0.9 kg CO2e/kg), low water usage',
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Almonds',
                'slug' => 'almonds',
                'category' => 'nut',
                'summary' => 'Nutritious tree nuts packed with healthy fats, protein, and vitamin E.',
                'description' => 'Almonds are edible seeds from the almond tree native to the Middle East. They are one of the most popular tree nuts worldwide and are prized for their delicate flavor and impressive nutrient profile.',
                'calories' => 579,
                'protein' => 21.2,
                'fat' => 49.9,
                'carbs' => 21.6,
                'fiber' => 12.5,
                'iron' => 3.7,
                'calcium' => 269,
                'zinc' => 3.1,
                'nutrition_extended' => json_encode([
                    'vitamin_e' => 25.6,
                    'magnesium' => 270,
                    'riboflavin' => 1.1
                ]),
                'availability' => 'common',
                'season' => 'fall',
                'storage_tips' => 'Store in an airtight container in a cool, dry place or refrigerate for extended freshness.',
                'cooking_methods' => json_encode(['raw', 'roast', 'blend', 'chop']),
                'common_uses' => json_encode(['snacking', 'almond-milk', 'baking', 'almond-butter', 'garnish']),
                'health_benefits' => json_encode([
                    'Rich in vitamin E',
                    'High in healthy fats',
                    'May lower cholesterol',
                    'Good for heart health',
                    'Supports skin health'
                ]),
                'dietary_flags' => json_encode(['gluten-free', 'soy-free']),
                'allergens' => json_encode(['tree-nuts']),
                'sustainability_score' => 5,
                'environmental_impact' => 'Medium-high carbon footprint (2.3 kg CO2e/kg), high water usage',
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Spinach',
                'slug' => 'spinach',
                'category' => 'vegetable',
                'summary' => 'Nutrient-dense leafy green rich in iron, vitamins, and antioxidants.',
                'description' => 'Spinach is a leafy green vegetable that originated in Persia. It is extremely nutrient-dense and provides a wide array of vitamins and minerals while being very low in calories.',
                'calories' => 23,
                'protein' => 2.9,
                'fat' => 0.4,
                'carbs' => 3.6,
                'fiber' => 2.2,
                'iron' => 2.7,
                'calcium' => 99,
                'nutrition_extended' => json_encode([
                    'vitamin_a' => 9377,
                    'vitamin_c' => 28,
                    'vitamin_k' => 483,
                    'folate' => 194
                ]),
                'availability' => 'common',
                'season' => 'spring, fall',
                'storage_tips' => 'Store unwashed in a plastic bag in the refrigerator for up to 5 days.',
                'cooking_methods' => json_encode(['raw', 'sauté', 'steam', 'blanch', 'wilt']),
                'common_uses' => json_encode(['salads', 'smoothies', 'sautés', 'soups', 'pasta']),
                'health_benefits' => json_encode([
                    'Extremely high in vitamins A, C, and K',
                    'Rich in antioxidants',
                    'Good source of iron',
                    'May improve eye health',
                    'Supports bone health'
                ]),
                'dietary_flags' => json_encode(['gluten-free', 'nut-free', 'soy-free']),
                'sustainability_score' => 10,
                'environmental_impact' => 'Very low carbon footprint (0.5 kg CO2e/kg), low water usage',
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Oat Milk',
                'slug' => 'oat-milk',
                'category' => 'milk_alternative',
                'summary' => 'Creamy plant-based milk made from oats, perfect for coffee and baking.',
                'description' => 'Oat milk is made by blending oats with water and straining the mixture. It has become incredibly popular due to its creamy texture, mild flavor, and sustainability compared to other milk alternatives.',
                'calories' => 47,
                'protein' => 1.0,
                'fat' => 1.5,
                'carbs' => 7.0,
                'fiber' => 0.8,
                'calcium' => 120,
                'vitamin_d' => 1.0,
                'vitamin_b12' => 0.4,
                'availability' => 'common',
                'season' => 'all year',
                'storage_tips' => 'Store unopened in a cool, dry place. Refrigerate after opening and use within 7-10 days.',
                'cooking_methods' => json_encode(['drink', 'bake', 'cook']),
                'common_uses' => json_encode(['coffee', 'smoothies', 'baking', 'cereal', 'cooking']),
                'health_benefits' => json_encode([
                    'Often fortified with calcium and vitamins',
                    'Naturally free from lactose and nuts',
                    'Contains beta-glucans',
                    'Low in saturated fat',
                    'Environmentally friendly'
                ]),
                'dietary_flags' => json_encode(['nut-free', 'soy-free']),
                'allergens' => json_encode(['gluten']),
                'sustainability_score' => 9,
                'environmental_impact' => 'Low carbon footprint (0.9 kg CO2e/kg), low water usage',
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Tempeh',
                'slug' => 'tempeh',
                'category' => 'protein',
                'summary' => 'Fermented soybean cake with a firm texture and nutty flavor, higher in protein than tofu.',
                'description' => 'Tempeh originated in Indonesia and is made by fermenting cooked soybeans with a beneficial fungus. The fermentation process creates a firm, cake-like product with a distinct nutty flavor and impressive nutritional profile.',
                'calories' => 193,
                'protein' => 20.3,
                'fat' => 10.8,
                'carbs' => 7.6,
                'fiber' => 4.0,
                'iron' => 2.7,
                'calcium' => 111,
                'nutrition_extended' => json_encode([
                    'magnesium' => 81,
                    'phosphorus' => 266,
                    'riboflavin' => 0.4
                ]),
                'availability' => 'common',
                'season' => 'all year',
                'storage_tips' => 'Keep refrigerated and use within 7-10 days of opening. Can be frozen for up to 3 months.',
                'cooking_methods' => json_encode(['steam', 'pan-fry', 'bake', 'grill', 'crumble']),
                'common_uses' => json_encode(['stir-fries', 'sandwiches', 'tacos', 'salads', 'bacon-alternative']),
                'health_benefits' => json_encode([
                    'Very high in protein',
                    'Contains probiotics from fermentation',
                    'Rich in minerals',
                    'May improve gut health',
                    'Good source of prebiotics'
                ]),
                'dietary_flags' => json_encode(['gluten-free', 'nut-free']),
                'allergens' => json_encode(['soy']),
                'sustainability_score' => 8,
                'environmental_impact' => 'Low carbon footprint (2.0 kg CO2e/kg), medium water usage',
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Brown Rice',
                'slug' => 'brown-rice',
                'category' => 'grain',
                'summary' => 'Whole grain rice with the bran layer intact, providing more fiber and nutrients than white rice.',
                'description' => 'Brown rice is whole grain rice with only the inedible outer hull removed. The bran and germ layers remain intact, providing significantly more fiber, vitamins, and minerals compared to white rice.',
                'calories' => 111,
                'protein' => 2.6,
                'fat' => 0.9,
                'carbs' => 23.0,
                'fiber' => 1.8,
                'iron' => 0.4,
                'zinc' => 0.6,
                'nutrition_extended' => json_encode([
                    'magnesium' => 43,
                    'manganese' => 0.9,
                    'selenium' => 9.8
                ]),
                'availability' => 'common',
                'season' => 'all year',
                'storage_tips' => 'Store in an airtight container in a cool, dry place. Keeps for 6 months or longer if refrigerated.',
                'cooking_methods' => json_encode(['boil', 'steam', 'pressure-cook']),
                'common_uses' => json_encode(['side-dishes', 'bowls', 'stir-fries', 'sushi', 'porridge']),
                'health_benefits' => json_encode([
                    'Whole grain with intact bran',
                    'Higher in fiber than white rice',
                    'Rich in manganese',
                    'May reduce diabetes risk',
                    'Supports digestive health'
                ]),
                'dietary_flags' => json_encode(['gluten-free', 'nut-free', 'soy-free']),
                'sustainability_score' => 6,
                'environmental_impact' => 'Medium carbon footprint (2.7 kg CO2e/kg), high water usage',
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Lentils',
                'slug' => 'lentils',
                'category' => 'legume',
                'summary' => 'Quick-cooking legumes packed with protein and fiber, available in red, green, and brown varieties.',
                'description' => 'Lentils are edible legumes that have been a dietary staple for thousands of years. Unlike other legumes, they cook relatively quickly and do not require soaking, making them convenient and nutritious.',
                'calories' => 116,
                'protein' => 9.0,
                'fat' => 0.4,
                'carbs' => 20.1,
                'fiber' => 7.9,
                'iron' => 3.3,
                'nutrition_extended' => json_encode([
                    'folate' => 181,
                    'potassium' => 369,
                    'phosphorus' => 180
                ]),
                'availability' => 'common',
                'season' => 'all year',
                'storage_tips' => 'Store dried lentils in an airtight container for up to 1 year. Cooked lentils keep for 3-5 days refrigerated.',
                'cooking_methods' => json_encode(['boil', 'pressure-cook', 'simmer']),
                'common_uses' => json_encode(['soups', 'dal', 'salads', 'veggie-burgers', 'stews']),
                'health_benefits' => json_encode([
                    'Excellent source of plant protein',
                    'Very high in fiber',
                    'Rich in iron and folate',
                    'May lower cholesterol',
                    'Supports heart health'
                ]),
                'dietary_flags' => json_encode(['gluten-free', 'nut-free', 'soy-free']),
                'sustainability_score' => 10,
                'environmental_impact' => 'Very low carbon footprint (0.9 kg CO2e/kg), low water usage',
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Nutritional Yeast',
                'slug' => 'nutritional-yeast',
                'category' => 'other',
                'summary' => 'Deactivated yeast with a cheesy, nutty flavor, often fortified with B12.',
                'description' => 'Nutritional yeast is a deactivated yeast, usually Saccharomyces cerevisiae, sold as yellow flakes or powder. It is popular among vegans for its cheese-like flavor and high vitamin B12 content when fortified.',
                'calories' => 320,
                'protein' => 50.0,
                'fat' => 5.0,
                'carbs' => 36.0,
                'fiber' => 20.0,
                'iron' => 2.1,
                'zinc' => 8.0,
                'vitamin_b12' => 8.0,
                'nutrition_extended' => json_encode([
                    'folate' => 2340,
                    'thiamin' => 10.0,
                    'riboflavin' => 9.7
                ]),
                'availability' => 'common',
                'season' => 'all year',
                'storage_tips' => 'Store in an airtight container in a cool, dry place for up to 2 years.',
                'cooking_methods' => json_encode(['sprinkle', 'mix', 'blend']),
                'common_uses' => json_encode(['popcorn-topping', 'cheese-sauce', 'seasoning', 'pasta', 'salads']),
                'health_benefits' => json_encode([
                    'Complete protein source',
                    'Fortified with vitamin B12',
                    'High in B vitamins',
                    'Contains antioxidants',
                    'Supports immune function'
                ]),
                'dietary_flags' => json_encode(['gluten-free', 'nut-free', 'soy-free']),
                'sustainability_score' => 9,
                'environmental_impact' => 'Low carbon footprint (1.0 kg CO2e/kg), low water usage',
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Sweet Potato',
                'slug' => 'sweet-potato',
                'category' => 'vegetable',
                'summary' => 'Starchy root vegetable rich in beta-carotene, fiber, and natural sweetness.',
                'description' => 'Sweet potatoes are root vegetables native to Central and South America. They are rich in beta-carotene, which gives them their orange color, and offer a naturally sweet flavor that works in both savory and sweet dishes.',
                'calories' => 86,
                'protein' => 1.6,
                'fat' => 0.1,
                'carbs' => 20.1,
                'fiber' => 3.0,
                'nutrition_extended' => json_encode([
                    'vitamin_a' => 14187,
                    'vitamin_c' => 2.4,
                    'potassium' => 337,
                    'manganese' => 0.3
                ]),
                'availability' => 'common',
                'season' => 'fall, winter',
                'storage_tips' => 'Store in a cool, dark, well-ventilated place. Do not refrigerate. Keeps for 2-3 weeks.',
                'cooking_methods' => json_encode(['bake', 'roast', 'steam', 'boil', 'microwave']),
                'common_uses' => json_encode(['baked', 'fries', 'mashed', 'soups', 'desserts']),
                'health_benefits' => json_encode([
                    'Extremely high in vitamin A',
                    'Rich in antioxidants',
                    'High in fiber',
                    'May improve blood sugar regulation',
                    'Supports eye health'
                ]),
                'dietary_flags' => json_encode(['gluten-free', 'nut-free', 'soy-free']),
                'sustainability_score' => 9,
                'environmental_impact' => 'Very low carbon footprint (0.4 kg CO2e/kg), low water usage',
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Chia Seeds',
                'slug' => 'chia-seeds',
                'category' => 'seed',
                'summary' => 'Tiny seeds packed with omega-3 fatty acids, fiber, and protein.',
                'description' => 'Chia seeds come from the plant Salvia hispanica, native to Mexico and Guatemala. These tiny seeds were a staple food for the Aztecs and Mayans and are now celebrated as a modern superfood.',
                'calories' => 486,
                'protein' => 16.5,
                'fat' => 30.7,
                'carbs' => 42.1,
                'fiber' => 34.4,
                'omega3' => 17.8,
                'calcium' => 631,
                'iron' => 7.7,
                'nutrition_extended' => json_encode([
                    'magnesium' => 335,
                    'phosphorus' => 860,
                    'manganese' => 2.7
                ]),
                'availability' => 'common',
                'season' => 'all year',
                'storage_tips' => 'Store in an airtight container in a cool, dry place for up to 2 years.',
                'cooking_methods' => json_encode(['soak', 'sprinkle', 'blend', 'mix']),
                'common_uses' => json_encode(['chia-pudding', 'smoothies', 'oatmeal', 'baking', 'egg-substitute']),
                'health_benefits' => json_encode([
                    'Excellent source of omega-3 fatty acids',
                    'Very high in fiber',
                    'Rich in antioxidants',
                    'Good source of plant protein',
                    'May support heart health'
                ]),
                'dietary_flags' => json_encode(['gluten-free', 'nut-free', 'soy-free']),
                'sustainability_score' => 8,
                'environmental_impact' => 'Low carbon footprint (1.2 kg CO2e/kg), low water usage',
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $count = 0;
        foreach ($ingredients as $data) {
            // Check if ingredient already exists
            $existing = Ingredient::findOne(['slug' => $data['slug']]);
            if ($existing) {
                $this->stdout("  - Skipping '{$data['name']}' (already exists)\n", \yii\helpers\Console::FG_GREY);
                continue;
            }

            $ingredient = new Ingredient();
            $ingredient->setAttributes($data, false);

            // Set timestamps
            if (!isset($data['created_at'])) {
                $ingredient->created_at = date('Y-m-d H:i:s');
            }
            if (!isset($data['updated_at'])) {
                $ingredient->updated_at = date('Y-m-d H:i:s');
            }

            if ($ingredient->save()) {
                $count++;
                $this->stdout("  ✓ Created '{$ingredient->name}'\n", \yii\helpers\Console::FG_GREEN);
            } else {
                $this->stdout("  ✗ Failed to create '{$data['name']}': " . json_encode($ingredient->errors) . "\n", \yii\helpers\Console::FG_RED);
            }
        }

        $this->stdout("\nSuccessfully created $count ingredients!\n", \yii\helpers\Console::FG_GREEN);
        return ExitCode::OK;
    }

    /**
     * Generate sample comparisons between ingredients
     */
    public function actionComparisons($count = 5)
    {
        $this->stdout("Generating sample comparisons...\n", \yii\helpers\Console::FG_YELLOW);

        // Get published ingredients
        $ingredients = Ingredient::find()->where(['status' => 'published'])->all();

        if (count($ingredients) < 2) {
            $this->stdout("Need at least 2 published ingredients to create comparisons.\n", \yii\helpers\Console::FG_RED);
            return ExitCode::DATAERR;
        }

        $generatorService = new ComparisonGeneratorService();
        $generated = 0;

        // Predefined interesting comparisons
        $pairs = [
            ['tofu', 'tempeh'],
            ['quinoa', 'brown-rice'],
            ['chickpeas', 'lentils'],
            ['almonds', 'chia-seeds'],
            ['oat-milk', 'almond-milk'],
        ];

        foreach ($pairs as $pair) {
            if ($generated >= $count) {
                break;
            }

            $ingredientA = Ingredient::findOne(['slug' => $pair[0], 'status' => 'published']);
            $ingredientB = Ingredient::findOne(['slug' => $pair[1], 'status' => 'published']);

            if (!$ingredientA || !$ingredientB) {
                $this->stdout("  - Skipping {$pair[0]} vs {$pair[1]} (ingredients not found)\n", \yii\helpers\Console::FG_GREY);
                continue;
            }

            // Check if comparison already exists
            $existing = Comparison::find()
                ->where([
                    'or',
                    [
                        'ingredient_a_id' => $ingredientA->id,
                        'ingredient_b_id' => $ingredientB->id,
                    ],
                    [
                        'ingredient_a_id' => $ingredientB->id,
                        'ingredient_b_id' => $ingredientA->id,
                    ]
                ])
                ->one();

            if ($existing) {
                $this->stdout("  - Skipping {$ingredientA->name} vs {$ingredientB->name} (already exists)\n", \yii\helpers\Console::FG_GREY);
                continue;
            }

            $comparison = $generatorService->generateComparison($ingredientA, $ingredientB);

            if ($comparison) {
                $generated++;
                $this->stdout("  ✓ Generated '{$comparison->title}'\n", \yii\helpers\Console::FG_GREEN);
            } else {
                $this->stdout("  ✗ Failed to generate comparison\n", \yii\helpers\Console::FG_RED);
            }
        }

        $this->stdout("\nSuccessfully generated $generated comparisons!\n", \yii\helpers\Console::FG_GREEN);
        return ExitCode::OK;
    }

    /**
     * Seed everything (ingredients + comparisons)
     */
    public function actionAll()
    {
        $this->actionIngredients();
        $this->stdout("\n");
        $this->actionComparisons(5);

        $this->stdout("\n=================================\n", \yii\helpers\Console::FG_GREEN);
        $this->stdout("Sample data seeding complete!\n", \yii\helpers\Console::FG_GREEN);
        $this->stdout("=================================\n\n", \yii\helpers\Console::FG_GREEN);
        $this->stdout("Visit your site to see the beautiful cards in action:\n", \yii\helpers\Console::FG_YELLOW);
        $this->stdout("  Frontend: http://localhost:8081\n", \yii\helpers\Console::FG_CYAN);
        $this->stdout("  Backend:  http://localhost:8080\n\n", \yii\helpers\Console::FG_CYAN);

        return ExitCode::OK;
    }
}
