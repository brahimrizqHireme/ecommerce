<?php

namespace App\Service\Stripe;

use App\Entity\Purchase;

interface PurchasePaymentInterface
{
    public function purchase(Purchase $purchase, string $stripeToken): array;
    public function getPaymentKey(): string;
}
