<?php

namespace App\Persister;

use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PurchaseDoctrinePersister implements PersisterInterface
{

    public function __construct(
        private Security $security,
        private EntityManagerInterface $em,
        private CartService $cartService,
    ) {
    }

    public function support(string $name = 'doctrine'): void
    {
        dump($name);
    }

    public function storePurchase(Purchase $purchase): void
    {
        $items = $this->cartService->getDetailsCartItems();

        $purchase->setPurchasedAt(new \DateTime)
            ->setUser($this->security->getUser())
            ->setTotal($this->cartService->getTotal());
        $this->em->persist($purchase);

        /**@var CartItem $item */
        foreach ($items as $item) {
            $purchaseItem = new PurchaseItem;
            $purchaseItem->setProduct($item->getProduct())
                ->setQuantity($item->getQuantity())
                ->setProductName($item->getProduct()->getName())
                ->setProductPrice($item->getProduct()->getPrice())
                ->setPurchase($purchase)
                ->setTotal($item->getTotal());

            $this->em->persist($purchaseItem);
        }

        $this->em->flush();
    }
}
