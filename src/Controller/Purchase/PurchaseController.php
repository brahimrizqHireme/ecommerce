<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class PurchaseController extends AbstractController
{

    public function __construct(
        private Security $security,
        private Environment $twig,
        private RouterInterface $router
    ) {
    }

    #[Route('/{_locale}/purchases', name: 'purchases_index', requirements: ['_locale' => '%app_locales%'], defaults: ['_locale' => '%locale%'])]
    public function index(): Response
    {
        /**@var User */
        $user = $this->security->getUser();

        if (!$user) {
            throw new AccessDeniedException();
        }

        $html = $this->twig->render('purchases/index.html.twig', [
            'purchases' => $user->getPruchases()
        ]);

        return new Response($html);
    }
}
