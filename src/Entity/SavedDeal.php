<?php

namespace App\Entity;

use App\Repository\SavedDealRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\GetSavedDealsByUserController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SavedDealRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource( 
    operations: [ 
        new GetCollection( 
            name: 'get_saved_deals_of_the_user', 
            uriTemplate: 'get_saved_deals_of_the_user', 
            controller: GetSavedDealsByUserController::class, 
            normalizationContext: ['groups' => ['deal:list']], 
            security: 'is_granted("ROLE_USER")', 
        ),
    ], 
)]
class SavedDeal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['deal:list'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'savedDeals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;

    #[ORM\ManyToOne(inversedBy: 'savedDeals')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['deal:list'])]
    private ?Deal $deal;

    #[ORM\Column]
    #[Groups(['deal:list'])]
    private \DateTimeImmutable $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDeal(): ?Deal
    {
        return $this->deal;
    }

    public function setDeal(?Deal $deal): self
    {
        $this->deal = $deal;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    
    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }
}
