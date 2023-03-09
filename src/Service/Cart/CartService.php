<?php

namespace App\Service\Cart;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{

    public function __construct(
        private readonly SessionInterface $session,
        private readonly ProductRepository $productRepository
    ) {
    }

    protected function getCart(): array
    {
        return $this->session->get('cart', []);
    }

    protected function saveCart(array $cart)
    {
        return $this->session->set('cart', $cart);
    }

    public function add(Product $product): void
    {
        $cart = $this->getCart();
        $cartQte = $this->session->get('cart_qte', 0);
        $total = $this->session->get('cart_total', 0);
        $id = $product->getId()->__toString();

        if (!isset($cart[$id])) {
            $cart[$id] = 0;
        }
        $cart[$id]++;

        $cartQte++;
        $total += $product->getPrice();

        $this->saveCart($cart);
        $this->session->set('cart_qte', $cartQte);
    }

    public function getDetailsCartItems(): array
    {
        $cartQte = 0;
        $items = [];
        $cartSession = $this->getCart();
        foreach ($cartSession as $uuid => $quantity) {
            $product = $this->productRepository->find($uuid);
            if (!$product) {
                unset($cartSession[$uuid]);
                continue;
            }
            $items[] = new CartItem($product, $quantity);
            $cartQte += $quantity;
        }
        $this->saveCart($cartSession);
        $this->session->set('cart_qte', $cartQte);

        return $items;
    }

    public function getTotal(): int
    {
        $cartSession = $this->getCart();
        $total = 0;

        foreach ($cartSession as $uuid => $quantity) {
            $product = $this->productRepository->find($uuid);
            if (!$product) {
                unset($cartSession[$uuid]);
                continue;
            }
            $total += ($product->getPrice() * $quantity);
        }
        return $total;
    }

    public function remove(string $productId): void
    {
        $cart = $this->getCart();
        unset($cart[$productId]);
        $this->saveCart($cart);
    }

    public function decrement(string $productId): void
    {
        $cart = $this->getCart();
        if (!isset($cart[$productId])) {
            return;
        }

        if ($cart[$productId] == 1) {
            $this->remove($productId);
            return;
        }
        $cart[$productId]--;
        $this->saveCart($cart);
    }
}
