<?php

namespace App\Controller\Dashboard;

use App\Entity\ProductType;
use App\Form\ProductTypeFormType;
use App\Repository\ProductTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ProductTypeController extends AbstractController
{
    #[Route('/product-types', name: 'product_types')]
    public function productTypes(ProductTypeRepository $productTypeRepository): Response
    {
        $types = $productTypeRepository->findAll();

        return $this->render('dashboard/product_types.html.twig', [
            'types' => $types
        ]);
    }

    #[Route('/product-types/new', name: 'add_new_product_type')]
    public function addNewProductType(Request $request, EntityManagerInterface $entityManager): Response
    {
        $type = new ProductType();

        $form = $this->createForm(ProductTypeFormType::class, $type);
        $form->handleRequest($request);

        $type = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($type);
            $entityManager->flush();

            return $this->redirectToRoute('product_types');
        }

        return $this->render('dashboard/new_product_type.html.twig', [
            'form' => $form->createView()
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/product-types/edit/{typeId}', name: 'edit_product_type')]
    public function editProductType($typeId, Request $request, EntityManagerInterface $entityManager, ProductTypeRepository $productTypeRepository): Response
    {
        $type = $productTypeRepository->findOneBy(['id' => $typeId]);

        $form = $this->createForm(ProductTypeFormType::class, $type);
        $form->handleRequest($request);

        $type = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($type);
            $entityManager->flush();

            return $this->redirectToRoute('product_types');
        }

        return $this->render('dashboard/edit_product_type.html.twig', [
            'form' => $form->createView()
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/product-types/delete/{typeId}', name: 'delete_product_type', methods: ['DELETE'])]
    public function deleteProductType($typeId, ProductTypeRepository $productTypeRepository, EntityManagerInterface $entityManager): Response
    {
        $type = $productTypeRepository->findOneBy(['id' => $typeId]);

        $entityManager->remove($type);
        $entityManager->flush();

        return new Response(null, 204);
    }
}