<?php

namespace App\Controller\Dashboard;

use App\Entity\StaticPage;
use App\Form\StaticPageFormType;
use App\Repository\StaticPageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class StaticPageController extends AbstractController
{
    #[Route('/static-pages', name: 'static_pages')]
    public function statisPages(StaticPageRepository $pageRepository): Response
    {
        $pages = $pageRepository->findAll();

        return $this->render('dashboard/static_pages.html.twig', [
            'pages' => $pages
        ]);
    }

    #[Route('/static-pages/new', name: 'add_new_static_page')]
    public function addNewStaticPage(Request $request, EntityManagerInterface $entityManager): Response
    {
        $page = new StaticPage();

        $form = $this->createForm(StaticPageFormType::class, $page);
        $form->handleRequest($request);

        $page = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $page->setCreatedAt(new \DateTimeImmutable("now"));
            $page->setUpdatedAt(new \DateTimeImmutable("now"));

            $entityManager->persist($page);
            $entityManager->flush();

            return $this->redirectToRoute('static_pages');
        }

        return $this->render('dashboard/new_static_page.html.twig', [
            'form' => $form->createView()
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/static-pages/edit/{pageId}', name: 'edit_static_page')]
    public function editStaticPage($pageId, Request $request, EntityManagerInterface $entityManager, StaticPageRepository $pageRepository): Response
    {
        $page = $pageRepository->findOneBy(['id' => $pageId]);

        $form = $this->createForm(StaticPageFormType::class, $page);
        $form->handleRequest($request);

        $page = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $page->setUpdatedAt(new \DateTimeImmutable("now"));

            $entityManager->persist($page);
            $entityManager->flush();

            return $this->redirectToRoute('static_pages');
        }

        return $this->render('dashboard/edit_static_page.html.twig', [
            'form' => $form->createView()
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/static-pages/delete/{pageId}', name: 'delete_static_page', methods: ['DELETE'])]
    public function deleteStaticPage($pageId, StaticPageRepository $pageRepository, EntityManagerInterface $entityManager): Response
    {
        $page = $pageRepository->findOneBy(['id' => $pageId]);

        $entityManager->remove($page);
        $entityManager->flush();

        return new Response(null, 204);
    }
}