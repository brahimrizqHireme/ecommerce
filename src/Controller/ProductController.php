<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use App\Enum\Requirement;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpCsFixer\DocBlock\ShortDescription;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormFactoryBuilder;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ProductRepository $productRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly SluggerInterface $slugger
    ) {
    }

    #[Route('/{slug}', name: 'product_category', priority: -1)]
    public function index(string $slug): Response
    {
        $category = $this->categoryRepository->findOneBy(['slug' => $slug]);
        if (!$category) {
            throw $this->createNotFoundException("Category was not found!");
        }
        return $this->render('product/category.html.twig', [
            'slig' => $slug,
            'category' => $category,
        ]);
    }

    #[Route('/{category_slug}/{slug}', name: 'product_show')]
    #[ParamConverter('product', class: 'App\Entity\Product', options: ['mapping' => ['slug' => 'slug']])]
    public function show(string $slug, ?Product $product)
    {
        // $product = $this->productRepository->findOneBy(['slug' => $slug]);

        if (!$product) {
            throw $this->createNotFoundException('Products was not found!');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('/admin/product/create', name: 'product_create')]
    public function create(
        Request $request
    ) {

        $product = new Product;
        $form = $this->createForm(ProductType::class, $product, [
            'validation_groups' => ['Default', 'group']
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($product);
            $this->em->flush();

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        return $this->render('product/admin/create.html.twig', ['formView' => $form->createView()]);
    }

    #[Route('/admin/product/{id}/edit', name: 'product_edit', requirements: ['id' => Requirement::UUID_V4])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function edit(
        Request $request,
        string $id,
        #[CurrentUser] User $user
    ) {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Products was not found!');
        }


        $form = $this->createForm(ProductType::class, $product, [
            'validation_groups' => ['Default', 'group']
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $product->setSlug(strtolower($this->slugger->slug($product->getName())));
            $this->em->flush();
            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }
        return $this->render('product/admin/edit.html.twig', ['formView' => $form->createView()]);
    }
}
