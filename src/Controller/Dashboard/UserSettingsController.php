<?php

namespace App\Controller\Dashboard;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class UserSettingsController extends AbstractController
{
    #[Route('/my-profile', name: 'my_profile')]
    public function myProfile(): Response
    {
        return $this->render('dashboard/my_profile.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    #[Route('/edit-my-profile', name: 'edit_my_profile')]
    public function editMyProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserFormType::class, $this->getUser());
        $form->handleRequest($request);

        $user = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTimeImmutable("now"));

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('my_profile');
        }

        return $this->render('dashboard/edit_my_profile.html.twig', [
            'form' => $form->createView()
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }
}