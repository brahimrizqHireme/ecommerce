<?php


namespace App\Service\Stripe;

use App\Entity\Purchase;
use Stripe\Stripe;
use Stripe\Charge;

class StripeService implements PurchasePaymentInterface
{

    public function __construct(
        private string $stripeSecret,
        private string $stripeKey,
    ) {
    }
    public function purchase(Purchase $purchase, string $stripeToken): array
    {
        // $_ENV["STRIPE_KEY"]
        Stripe::setApiKey($_ENV['STRIPE_SECRET']);
        $result = Charge::create([
            "amount" => $purchase->getTotal(),
            "currency" => "eur",
            "source" => $stripeToken,
            "description" => $purchase->getFullName()
        ]);

        return $result->toArray() ?? [];
    }

    public function getPaymentKey(): string
    {
        return $this->stripeKey;
    }
}
