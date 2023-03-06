<?php

namespace App\Controller;

use App\Entity\Category;
use App\Enum\Requirement;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private CategoryRepository $categoryRepository,
        private SluggerInterface $slugger
    ) {
    }

    public function renderMenuList()
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('category/_menu.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/admin/category/create', name: 'categoty_create')]
    public function create(Request $request): Response
    {
        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug($this->slugger->slug(strtolower($category->getName())));
            $this->em->persist($category);
            $this->em->flush();
            return $this->redirectToRoute('homepage');
            // $form = $this->createForm(CategoryType::class, new Category);
        }
        return $this->render('category/admin/create.html.twig', [
            'formView' => $form->createView(),
        ]);
    }

    #[Route('/admin/category/{id}/edit', name: "category_edit", requirements: ['id' => Requirement::UUID_V4])]
    public function edit(Request $request, string $id)
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            throw $this->createNotFoundException('Products was not found!');
        }
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug($this->slugger->slug(strtolower($category->getName())));
            $this->em->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'category/admin/edit.html.twig',
            [
                'category' => $category,
                'formView' => $form->createView()
            ]
        );
    }
}
