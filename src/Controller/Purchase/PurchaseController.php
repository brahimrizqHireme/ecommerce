<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use App\Repository\PurchaseRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Twig\Environment;

class PurchaseController extends AbstractController
{

    public function __construct(
        private PurchaseRepository $pruchaseRepository
    ) {
    }

    #[Route('/{_locale}/purchases', name: 'purchases_index', requirements: ['_locale' => '%app_locales%'], defaults: ['_locale' => '%locale%'])]
    #[IsGranted('ROLE_USER')]
    public function index(
        #[CurrentUser] User $user
    ): Response {

        $purchases = $this->pruchaseRepository->findBy(['user' => $user], ['purchasedAt' => 'desc']);

        return $this->render('purchases/index.html.twig', [
            'purchases' => $purchases
        ]);
    }
}
