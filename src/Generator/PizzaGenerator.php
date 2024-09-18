<?php

namespace App\Generator;

use App\Entity\GrumpyPizza;
use App\Repository\IngredientRepository;

readonly class PizzaGenerator
{
    public function __construct(
        private IngredientRepository $ingredientRepository,
    ) {
    }

    public function generate(string $name, int $ingredientsCount): GrumpyPizza
    {
        $allIngredients = $this->ingredientRepository->findAll();
        $pizza = (new GrumpyPizza())
            ->setName($name)
            ->setSize(26)
        ;
        $selectedIngredients = array_rand($allIngredients, $ingredientsCount);
        if (is_int($selectedIngredients)) {
            $selectedIngredients = [$selectedIngredients];
        }
        foreach ($selectedIngredients as $ingredient) {
            $pizza->addComposedWith($allIngredients[$ingredient]);
        }

        return $pizza;
    }
}
