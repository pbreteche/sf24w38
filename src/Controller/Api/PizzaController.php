<?php

namespace App\Controller\Api;

use App\Repository\GrumpyPizzaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/pizza', defaults: ['_format' => 'json'])]
class PizzaController extends AbstractController
{
    #[Route(methods: 'GET')]
    public function index(
        GrumpyPizzaRepository $repository,
    ): JsonResponse {
        $pizzas = $repository->findByNameStartingWith();

        return $this->json($pizzas);
    }

    #[Route('/short', methods: 'GET')]
    public function indexShort(
        GrumpyPizzaRepository $repository,
    ): JsonResponse {
        $pizzas = $repository->findByNameStartingWith();

        return $this->json($pizzas, context: [
            'groups' => 'short',
        ]);
    }
}
