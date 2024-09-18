<?php

namespace App\Controller;

use App\Entity\GrumpyPizza;
use App\Entity\Ingredient;
use App\Form\GrumpyPizzaType;
use App\Repository\GrumpyPizzaRepository;
use App\Services\ServiceDemo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/grumpy-pizza')]
class GrumpyPizzaController extends AbstractController
{
    #[Route]
    public function index(
        Request $request,
        GrumpyPizzaRepository $repository,
        ValidatorInterface $validator,
    ): Response {
        try {
            $page = $request->query->getInt('page', 1);
        } catch (\Throwable) {
            return $this->redirectToRoute($request->attributes->get('_route'));
        }

        /* Exemple utilisation du validator component
        $violationList = $validator->validate(10000, new Choice(choices: [5, 10, 20]));
        if (0 < $violationList->count()) {
        }
        */

        $nameFilter = $request->query->get('name');
        $offset = GrumpyPizzaRepository::MAX_RESULT_PER_PAGE * (max($page, 1) - 1);
        $pizzas = $repository->findByNameStartingWith($nameFilter, $offset);

        return $this->render('grumpy_pizza/index.html.twig', [
            'pizzas' => $pizzas,
        ]);
    }

    #[IsGranted('PIZZA_SHOW', 'pizza', statusCode: 404)]
    #[Route('/{id}', requirements: ['id' => '\d+'], methods: 'GET')]
    public function show(
        GrumpyPizza $pizza,
    ): Response {
        return $this->render('grumpy_pizza/show.html.twig', [
            'pizza' => $pizza,
        ]);
    }

    #[Route('/new', methods: ['GET', 'POST'])]
    #[IsGranted(new Expression('is_granted("ROLE_🍕") or is_granted("ROLE_🧀")'))]
    #[IsGranted('IS_WORKED')]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
    ): Response {
        $pizza = (new GrumpyPizza())->setSize(32);
        $form = $this->createForm(GrumpyPizzaType::class, $pizza, [
            'without_size' => true,
            'method' => 'GET',
        ]);

        $form->handleRequest($request);

        if ($this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('info', 'your IP is ...');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($pizza);
            $manager->flush();
            $this->addFlash('success', 'La pizza a été enregistrée.');

            return $this->redirectToRoute('app_grumpypizza_show', [
                'id' => $pizza->getId(),
            ]);
        }

        return $this->render('grumpy_pizza/new.html.twig', [
            'new_form' => $form->createView(),
        ]);
    }

    #[Route(path: '/add-ingredient', methods: ['GET', 'POST'])]
    public function addIngredient(
        Request $request,
        EntityManagerInterface $manager,
    ): Response {
        $ingredient = new Ingredient();
        $form = $this->createFormBuilder($ingredient)->add('name')->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ingredient);
            $manager->flush();
            $this->addFlash('success', 'L\'ingrédient a été ajouté.');

            return $this->redirectToRoute('app_grumpypizza_addingredient');
        }

        return $this->render('grumpy_pizza/add_ingredient.html.twig', [
            'new_form' => $form->createView(),
        ]);
    }


    #[Route('/{id}/edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        GrumpyPizza $pizza,
        EntityManagerInterface $manager,
        ServiceDemo $demo,
    ): Response {
        $form = $this->createForm(GrumpyPizzaType::class, $pizza, [
            'without_size' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demo->example();
            $manager->flush();
            $this->addFlash('success', 'La pizza a été modifiée.');

            return $this->redirectToRoute('app_grumpypizza_show', [
                'id' => $pizza->getId(),
            ]);
        }

        return $this->render('grumpy_pizza/edit.html.twig', [
            'edit_form' => $form->createView(),
        ]);
    }
}
