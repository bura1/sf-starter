<?php

namespace App\Controller\Dashboard;

use App\Entity\Badge;
use App\Form\BadgeFormType;
use App\Repository\BadgeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class BadgeController extends AbstractController
{
    #[Route('/badges', name: 'badges')]
    public function badges(BadgeRepository $badgeRepository): Response
    {
        $badges = $badgeRepository->findAll();

        return $this->render('dashboard/badges.html.twig', [
            'badges' => $badges
        ]);
    }

    #[Route('/badges/new', name: 'add_new_badge')]
    public function addNewBadge(Request $request, EntityManagerInterface $entityManager): Response
    {
        $badge = new Badge();

        $form = $this->createForm(BadgeFormType::class, $badge);
        $form->handleRequest($request);

        $badge = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($badge);
            $entityManager->flush();

            return $this->redirectToRoute('badges');
        }

        return $this->render('dashboard/new_badge.html.twig', [
            'form' => $form->createView()
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/badges/edit/{badgeId}', name: 'edit_badge')]
    public function editBadge($badgeId, Request $request, EntityManagerInterface $entityManager, BadgeRepository $badgeRepository): Response
    {
        $badge = $badgeRepository->findOneBy(['id' => $badgeId]);

        $form = $this->createForm(BadgeFormType::class, $badge);
        $form->handleRequest($request);

        $badge = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($badge);
            $entityManager->flush();

            return $this->redirectToRoute('badges');
        }

        return $this->render('dashboard/edit_badge.html.twig', [
            'form' => $form->createView()
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/badges/delete/{badgeId}', name: 'delete_badge', methods: ['DELETE'])]
    public function deleteBadge($badgeId, BadgeRepository $badgeRepository, EntityManagerInterface $entityManager): Response
    {
        $badge = $badgeRepository->findOneBy(['id' => $badgeId]);

        $entityManager->remove($badge);
        $entityManager->flush();

        return new Response(null, 204);
    }
}