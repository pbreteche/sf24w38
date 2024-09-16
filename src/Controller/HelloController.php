<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/hello-world', defaults: ['_format' => 'json'])]
class HelloController extends AbstractController
{
    #[Route('', methods: 'GET')]
    #[Route(
        '/{id}/{month}',
        name: 'app_hello_demoroute',
        requirements: ['id' => '\d+', 'month' => '\d{4}-(0\d|1{012})'],
        defaults: ['month' => '2024-09'],
        methods: ['GET', 'PUT'],

    )]
    public function helloWorld(
        Request $request,
        int $id = null,
        \DateTimeImmutable $month = null,
    ): Response {
        dump($id, $month);

        $locale = $request->query->get('locale', 'fr');

        return new Response(sprintf('Hello world!%s</body>', $locale));
    }
}
