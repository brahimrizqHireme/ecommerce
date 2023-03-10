<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Entity\User;
use App\Form\CartConfirmationType;
use App\Service\Cart\CartItem;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class PurchaseConfirmationController extends AbstractController
{

    public function __construct(
        private Security $security,
        private EntityManagerInterface $em,
        private FormFactoryInterface $formFactory,
        private Environment $twig,
        private CartService $cartService,
        private RouterInterface $router
    ) {
    }

    #[Route('/{_locale}/purchases/confirm', name: 'purchase_confirm', requirements: ['_locale' => '%app_locales%'], defaults: ['_locale' => '%locale%'])]
    #[IsGranted('ROLE_USER')]
    public function confirm(Request $request): Response
    {
        /**@var User */
        $user = $this->getUser();
        $form = $this->formFactory->create(CartConfirmationType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            $this->addFlash('warning', 'Please fill in the form!');
            return $this->redirectToRoute('cart_show');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $items = $this->cartService->getDetailsCartItems();

            if ($items < 1) {
                $this->addFlash('warning', 'Cart is Empty!');
                return $this->redirectToRoute('cart_show');
            }

            /**@var Purchase */
            $purchase = $form->getData();
            $purchase->setPurchasedAt(new \DateTime)
                ->setUser($user)
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
            $this->cartService->empty();
        }

        $this->addFlash('success', 'Command was confirmed, Thank u!');
        return $this->redirectToRoute('purchases_index');
    }
}
