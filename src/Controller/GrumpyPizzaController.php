<?php

namespace App\Controller;

use App\Repository\GrumpyPizzaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/grumpy-pizza')]
class GrumpyPizzaController extends AbstractController
{
    #[Route]
    public function index(
        GrumpyPizzaRepository $repository,
    ): Response {
        $pizzas = $repository->findAll();

        return $this->render('grumpy_pizza/index.html.twig', [
            'pizzas' => $pizzas,
        ]);
    }
}
