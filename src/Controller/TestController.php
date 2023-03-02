<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/{name}', name: 'index_test')]
    public function index(string $name): Response
    {
        return $this->render(
            'test_index.html.twig',
            [
                'name' => $name,
                'age' => 18,
                'formateur1' => [
                    'name' => 'Brahim',
                    'email' => 'bra@gmail.com',
                    'age' => 25,
                ],
                'formateur2' => [
                    'name' => 'Lior',
                    'email' => 'mior@gmail.com',
                    'age' => 25,
                ],
                'cities' => [
                    'casablanca',
                    'rabat',
                    'sale',
                ],
            ]
        );
    }

    #[Route(
        "/test/{age<\d+>?10}",
        name: 'test_app',
        methods: ['GET'],
        schemes: ['https', 'http'],
        host: 'ecommerce.dev',
        defaults: ['age' => 30]
    )]
    public function test(Request $request, int $age): Response
    {
        $request->attributes->get('age', 0);
        return new Response("test {$age}");
    }
}
