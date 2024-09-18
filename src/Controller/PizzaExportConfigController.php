<?php

namespace App\Controller;

use App\Entity\PizzaExportConfig;
use App\Form\PizzaExportConfigType;
use App\Repository\PizzaExportConfigRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pizza/export/config')]
final class PizzaExportConfigController extends AbstractController
{
    #[Route(name: 'app_pizza_export_config_index', methods: ['GET'])]
    public function index(PizzaExportConfigRepository $pizzaExportConfigRepository): Response
    {
        return $this->render('pizza_export_config/index.html.twig', [
            'pizza_export_configs' => $pizzaExportConfigRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pizza_export_config_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pizzaExportConfig = new PizzaExportConfig();
        $form = $this->createForm(PizzaExportConfigType::class, $pizzaExportConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pizzaExportConfig->updateOwners();
            $entityManager->persist($pizzaExportConfig);
            $entityManager->flush();

            return $this->redirectToRoute('app_pizza_export_config_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pizza_export_config/new.html.twig', [
            'pizza_export_config' => $pizzaExportConfig,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pizza_export_config_show', methods: ['GET'])]
    public function show(PizzaExportConfig $pizzaExportConfig): Response
    {
        return $this->render('pizza_export_config/show.html.twig', [
            'pizza_export_config' => $pizzaExportConfig,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pizza_export_config_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PizzaExportConfig $pizzaExportConfig, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PizzaExportConfigType::class, $pizzaExportConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pizzaExportConfig->updateOwners();
            $entityManager->flush();

            return $this->redirectToRoute('app_pizza_export_config_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pizza_export_config/edit.html.twig', [
            'pizza_export_config' => $pizzaExportConfig,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pizza_export_config_delete', methods: ['POST'])]
    public function delete(Request $request, PizzaExportConfig $pizzaExportConfig, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pizzaExportConfig->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pizzaExportConfig);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pizza_export_config_index', [], Response::HTTP_SEE_OTHER);
    }
}
