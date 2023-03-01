<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{

    public function index()
    {
        return new Response();
    }

    #[Route("/test/{age<\d+>?10}", name: "test_app", methods: ["GET"], schemes: ["https", "http"], host: "ecommerce.dev", defaults: ["age" => 30])]
    public function test(Request $request, int $age)
    {
        // $age = $request->attributes->get('age', 0);
        return new Response("test {$age}");
    }
}
