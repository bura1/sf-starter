<?php

namespace App\Controller\Dashboard;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ProductController extends AbstractController
{
    #[Route('/products', name: 'products')]
    public function products(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('dashboard/products.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/products/new', name: 'add_new_product')]
    public function addNewProduct(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        $product = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreatedAt(new \DateTimeImmutable("now"));
            $product->setUpdatedAt(new \DateTimeImmutable("now"));
            $product->setStatus('active');
            $product->setCurrency('USD');
            $product->setUser($this->getUser());

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('products');
        }

        return $this->render('dashboard/new_product.html.twig', [
            'form' => $form->createView()
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/products/edit/{productId}', name: 'edit_product')]
    public function editProduct($productId, Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy(['id' => $productId]);

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        $product = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUpdatedAt(new \DateTimeImmutable("now"));

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('products');
        }

        return $this->render('dashboard/edit_product.html.twig', [
            'form' => $form->createView()
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/products/delete/{productId}', name: 'delete_product', methods: ['DELETE'])]
    public function deleteProduct($productId, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $product = $productRepository->findOneBy(['id' => $productId]);

        $entityManager->remove($product);
        $entityManager->flush();

        return new Response(null, 204);
    }
}