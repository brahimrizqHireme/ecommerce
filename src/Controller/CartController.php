<?php

namespace App\Controller;

use App\Entity\Product;
use App\Enum\Requirement;
use App\Form\CartConfirmationType;
use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    public function __construct(private readonly CartService $cartService)
    {
    }



    #[Route('/cart/{id}/add', name: 'cart_add', methods: ['GET'], requirements: ['id' => Requirement::UUID_V4])]
    #[ParamConverter('product', class: Product::class, options: ['mapping' => ['id' => 'id']])]
    public function add(
        ?Product $product,
        Request $request
    ): Response {

        if (!$product) {
            throw new NotFoundHttpException();
        }

        $this->cartService->add($product);
        $this->addFlash('success', 'Product has been added to cart');

        $route = $request->headers->get('referer');
        return $this->redirect($route);
        // return $this->redirectToRoute('product_show', [
        //     'slug' => $product->getSlug(),
        //     'category_slug' => $product->getCategory()->getSlug()
        // ]);
    }

    #[Route('/card', name: 'cart_show', methods: ['GET'])]
    public function show()
    {
        $form = $this->createForm(CartConfirmationType::class);
        return $this->render('cart/show.html.twig', [
            'items' => $this->cartService->getDetailsCartItems(),
            'formView' => $form->createView()
        ]);
    }

    #[Route('/cart/{id}/delete', name: 'cart_delete', methods: ['GET'], requirements: ['id' => Requirement::UUID_V4])]
    #[ParamConverter('product', class: Product::class, options: ['mapping' => ['id' => 'id']])]
    public function delete(
        string $id,
        ?Product $product,
    ): Response {

        if (!$product) {
            throw new NotFoundHttpException();
        }

        $this->cartService->remove($id);
        $this->addFlash('danger', 'Product was deleted');
        return $this->redirectToRoute('cart_show');
    }


    #[Route('/cart/{id}/decrement', name: 'cart_decrement', methods: ['GET'], requirements: ['id' => Requirement::UUID_V4])]
    #[ParamConverter('product', class: Product::class, options: ['mapping' => ['id' => 'id']])]
    public function decrement(
        string $id,
        ?Product $product,
    ): Response {

        if (!$product) {
            throw new NotFoundHttpException();
        }

        $this->cartService->decrement($id);
        $this->addFlash('warning', 'Product was decremented!');
        return $this->redirectToRoute('cart_show');
    }
}
