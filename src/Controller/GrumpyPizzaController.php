<?php

namespace App\Controller;

use App\Entity\GrumpyPizza;
use App\Repository\GrumpyPizzaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Choice;
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

    #[Route('/{id}', requirements: ['id' => '\d+'], methods: 'GET')]
    public function show(
        GrumpyPizza $pizza,
    ): Response {
        return $this->render('grumpy_pizza/show.html.twig', [
            'pizza' => $pizza,
        ]);
    }

    #[Route('/new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
    ): Response {
        $pizza = new GrumpyPizza();
        $form = $this->createFormBuilder($pizza)
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nom',
            ])
            ->add('size')
            ->getForm()
        ;

        $form->handleRequest($request);

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
}
