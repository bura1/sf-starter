<?php

namespace App\Controller\Files;

use App\Entity\ProfilePicture;
use App\Repository\UserRepository;
use App\Service\FileHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ProfilePictureController extends AbstractController
{
    #[Route(path: '/upload-profile-picture/{userId}', name: 'upload_profile_picture', methods: ['POST'])]
    public function uploadProfilePicture($userId, UserRepository $userRepository, Request $request, ValidatorInterface $validator, FileHelper $fileHelper, EntityManagerInterface $entityManager)
    {
        $uploadedFile = $request->files->get('filepond');
        $originalFilename = $uploadedFile->getClientOriginalName();

        $violations = $validator->validate(
            $uploadedFile,
            [
                new File([
                    'maxSize' => '10M',
                    'mimeTypes' => [
                        'application/pdf',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'image/jpeg',
                        'image/png'
                    ]
                ])
            ]
        );

        if ($violations->count() > 0) {
            return $this->json($violations, 400);
        }

        $filename = $fileHelper->uploadProfilePicture($uploadedFile);

        $user = $userRepository->findOneBy(['id' => $userId]);

        $profilePicture = new ProfilePicture();
        $profilePicture->setUser($user);
        $profilePicture->setName($filename);
        $profilePicture->setOriginalName($originalFilename ?? $filename);
        $profilePicture->setMimeType($uploadedFile->getMimeType() ?? 'application/octet-stream');
        $profilePicture->setSize($uploadedFile->getSize());
        $profilePicture->setUploadedAt(new \DateTimeImmutable('now'));

        $user->setProfilePicture($profilePicture);

        if (is_file($uploadedFile->getPathname())) {
            unlink($uploadedFile->getPathname());
        }

        $entityManager->persist($user);
        $entityManager->persist($profilePicture);
        $entityManager->flush();

        return $this->json(
            $profilePicture,
            201,
            [],
            [
                'groups' => ['main']
            ]
        );
    }

    #[Route(path: '/delete-profile-picture/{userId}', name: 'delete_profile_picture', methods: ['DELETE'])]
    public function deleteProfilePicture($userId, UserRepository $userRepository, FileHelper $fileHelper, EntityManagerInterface $entityManager)
    {
        $user = $userRepository->findOneBy(['id' => $userId]);
        $profilePicture = $user->getProfilePicture();
        $user->setProfilePicture(null);

        $fileHelper->deleteFile($profilePicture->getFilePath());

        $entityManager->persist($user);
        $entityManager->flush();

        return new Response(null, 204);
    }
}