<?php

namespace App\Controller;

use App\Entity\GrumpyPizza;
use App\Repository\GrumpyPizzaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/grumpy-pizza')]
class GrumpyPizzaController extends AbstractController
{
    #[Route]
    public function index(
        Request $request,
        GrumpyPizzaRepository $repository,
    ): Response {
        try {
            $page = $request->query->getInt('page', 1);
        } catch (\Throwable) {
            return $this->redirectToRoute($request->attributes->get('_route'));
        }
        $limitPerPage = 10;
        $offset = $limitPerPage * (max($page, 1) - 1);
        $pizzas = $repository->findBy([], ['name' => 'ASC'], $limitPerPage, $offset);

        return $this->render('grumpy_pizza/index.html.twig', [
            'pizzas' => $pizzas,
        ]);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], methods: 'GET')]
    public function show(
        GrumpyPizza $pizza,
    ): Response {
        return $this->render('grumpy_pizza/show.html.twig', [
            'pizza' => $pizza,
        ]);
    }
}
