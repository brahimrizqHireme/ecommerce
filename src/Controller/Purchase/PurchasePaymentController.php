<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Enum\Requirement;
use App\Repository\PurchaseRepository;
use App\Service\Cart\CartService;
use App\Service\Stripe\PurchasePaymentInterface;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Stripe\PaymentIntent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PurchasePaymentController extends AbstractController
{

    public function __construct(
        private readonly CartService $cartService,
        private PurchasePaymentInterface $purchasePayment,
        private EntityManagerInterface $em,
        private PurchaseRepository $purchaseRepository

    ) {
    }

    #[Route('/stripe/create-charge/{id}', name: 'app_stripe_charge', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    #[ParamConverter('purchase', class: Purchase::class, options: ['mapping' => ['id' => 'id']])]
    public function createCharge(Request $request, Purchase $purchase)
    {
        $stripeToken = $request->request->get('stripeToken');
        $result = $this->purchasePayment->purchase($purchase, $stripeToken);
        $this->addFlash('success', 'Payment Successful!');
        if ($result['status'] === 'succeeded' && $result['paid']) {
            $purchase->setStatus(Purchase::PAID_STATUS);
            $this->em->flush();
            $this->cartService->empty();
        }

        return $this->redirectToRoute('purchases_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{_locale}/purchase/payment/{id}', name: 'purchase_payment_form', requirements: ['id' => Requirement::UUID_V4, '_locale' => '%app_locales%'], defaults: ['_locale' => '%locale%'])]
    #[IsGranted('ROLE_USER')]
    #[ParamConverter('purchase', class: Purchase::class, options: ['mapping' => ['id' => 'id']])]
    public function showPaymentCart(Request $request, Purchase $purchase)
    {
        if (!$purchase) {
            throw new NotFoundHttpException();
        }

        if ($purchase->getStatus() === Purchase::PAID_STATUS) {
            throw new InvalidArgumentException('Command already paid!');
        }

        return $this->render('purchases/payment.html.twig', [
            'stripe_key' => $this->purchasePayment->getPaymentKey(),
            'purchase' => $purchase
        ]);
    }
}
