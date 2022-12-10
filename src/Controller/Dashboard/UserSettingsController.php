<?php

namespace App\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class UserSettingsController extends AbstractController
{
    #[Route('/my-profile', name: 'my_profile')]
    public function dashboard(): Response
    {
        return $this->render('dashboard/my_profile.html.twig');
    }
}