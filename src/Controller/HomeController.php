<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryBuilder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HomeController extends AbstractController
{

    #[Route('/', name: 'homepage')]
    public function home(ProductRepository $productRepository, ValidatorInterface $validator): Response
    {
        // $product = new Product;

        // $product->setName('22');
        // $product->setPrice(5);
        // $result = $validator->validate($product);
        $products = $productRepository->findBy([], [], 3);
        return $this->render('home.html.twig', [
            'products' => $products
        ]);
    }
}
