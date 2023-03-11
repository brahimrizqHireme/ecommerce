<?php

namespace App\Persister;


use App\Entity\Purchase;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

// #[AutoconfigureTag('app.purchase.persister')]
interface PersisterInterface
{
    public function storePurchase(Purchase $urchase): void;
    public function support(string $name): void;
}
