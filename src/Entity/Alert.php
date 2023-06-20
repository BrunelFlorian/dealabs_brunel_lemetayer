<?php

namespace App\Entity;

use App\Repository\AlertRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlertRepository::class)]
class Alert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'alerts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $keyword = null;

    #[ORM\Column]
    private ?int $minTemperature = null;

    #[ORM\Column(length: 255)]
    private ?string $notificationFrequency = null;

    #[ORM\Column]
    private ?bool $emailNotificationEnabled = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function setKeyword(string $keyword): static
    {
        $this->keyword = $keyword;

        return $this;
    }

    public function getMinTemperature(): ?int
    {
        return $this->minTemperature;
    }

    public function setMinTemperature(int $minTemperature): static
    {
        $this->minTemperature = $minTemperature;

        return $this;
    }

    public function getNotificationFrequency(): ?string
    {
        return $this->notificationFrequency;
    }

    public function setNotificationFrequency(string $notificationFrequency): static
    {
        $this->notificationFrequency = $notificationFrequency;

        return $this;
    }

    public function isEmailNotificationEnabled(): ?bool
    {
        return $this->emailNotificationEnabled;
    }

    public function setEmailNotificationEnabled(bool $emailNotificationEnabled): static
    {
        $this->emailNotificationEnabled = $emailNotificationEnabled;

        return $this;
    }
}
