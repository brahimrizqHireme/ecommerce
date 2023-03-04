<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function home(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy([], [], 3);
        return $this->render('home.html.twig', [
            'products' => $products
        ]);
    }
}
