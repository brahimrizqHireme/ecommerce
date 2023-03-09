<?php

namespace App\Service\Cart;

use App\Entity\Product;
use ArrayObject;

class CartItem
{

    public function __construct(
        private Product $product,
        private int $quantity = 1
    ) {
    }

    public function getTotal(): int
    {
        return $this->product->getPrice() * $this->quantity;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getProductId(): string
    {
        return $this->getProduct()->getId()->__toString();
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function incQuantity(int $qte): void
    {
        $this->quantity += $qte;
    }
}
