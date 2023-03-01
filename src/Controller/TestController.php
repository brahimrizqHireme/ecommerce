<?php

namespace App\Controller;

use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    private LoggerInterface $logger;
    public function __construct(LoggerInterface $logger, int $tva, Slugify $slugify)
    {
        dd($slugify);
        $this->logger = $logger;
    }

    #[Route("/", name: "index_test")]
    public function index(int $tva)
    {
        $this->logger->info('sssssssssssssssssssssssss log');
        return new Response("test index $tva");
    }

    #[Route("/test/{age<\d+>?10}", name: "test_app", methods: ["GET"], schemes: ["https", "http"], host: "ecommerce.dev", defaults: ["age" => 30])]
    public function test(Request $request, int $age)
    {
        // $age = $request->attributes->get('age', 0);
        return new Response("test {$age}");
    }
}
