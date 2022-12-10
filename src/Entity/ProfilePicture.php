<?php

namespace App\Entity;

use App\Repository\ProfilePictureRepository;
use App\Service\FileHelper;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfilePictureRepository::class)]
class ProfilePicture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\JoinColumn(nullable: true)]
    private ?User $user;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $originalName;

    #[ORM\Column(type: 'integer')]
    private $size;

    #[ORM\Column(type: 'string', length: 255)]
    private $mimeType;

    #[ORM\Column(type: 'datetime_immutable')]
    private $uploadedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getUploadedAt(): ?\DateTimeInterface
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(\DateTimeInterface $uploadedAt): self
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }

    public function getFilePath(): string
    {
        return FileHelper::PROFILE_PICTURE.'/'.$this->getName();
    }
}
