<?php

namespace App\Controller\Dashboard;

use App\Entity\ProductCategory;
use App\Form\ProductCategoryFormType;
use App\Repository\ProductCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ProductCategoryController extends AbstractController
{
    #[Route('/product-categories', name: 'product_categories')]
    public function productCategories(ProductCategoryRepository $productCategoryRepository): Response
    {
        $categories = $productCategoryRepository->findAll();

        return $this->render('dashboard/product_categories.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/product-categories/new', name: 'add_new_product_category')]
    public function addNewProductCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new ProductCategory();

        $form = $this->createForm(ProductCategoryFormType::class, $category);
        $form->handleRequest($request);

        $category = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('product_categories');
        }

        return $this->render('dashboard/new_product_category.html.twig', [
            'form' => $form->createView()
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/product-categories/edit/{categoryId}', name: 'edit_product_category')]
    public function editProductCategories($categoryId, Request $request, EntityManagerInterface $entityManager, ProductCategoryRepository $productCategoryRepository): Response
    {
        $category = $productCategoryRepository->findOneBy(['id' => $categoryId]);

        $form = $this->createForm(ProductCategoryFormType::class, $category);
        $form->handleRequest($request);

        $category = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('product_categories');
        }

        return $this->render('dashboard/edit_product_category.html.twig', [
            'form' => $form->createView()
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/product-categories/delete/{categoryId}', name: 'delete_product_category', methods: ['DELETE'])]
    public function deleteProductCategory($categoryId, ProductCategoryRepository $productCategoryRepository, EntityManagerInterface $entityManager): Response
    {
        $category = $productCategoryRepository->findOneBy(['id' => $categoryId]);

        $entityManager->remove($category);
        $entityManager->flush();

        return new Response(null, 204);
    }
}