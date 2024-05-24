<?php

namespace Gamification\Entity;

use App\Entity\Student;
use Doctrine\ORM\Mapping as ORM;
use Gamification\Repository\StudentBadgeRepository;

#[ORM\Entity(repositoryClass: StudentBadgeRepository::class)]
class StudentBadge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'studentBadges')]
    private ?Student $student = null;

    #[ORM\ManyToOne(inversedBy: 'studentBadges')]
    private ?Badge $badge = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $awardedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): static
    {
        $this->student = $student;

        return $this;
    }

    public function getBadge(): ?Badge
    {
        return $this->badge;
    }

    public function setBadge(?Badge $badge): static
    {
        $this->badge = $badge;

        return $this;
    }

    public function getAwardedAt(): ?\DateTimeImmutable
    {
        return $this->awardedAt;
    }

    public function setAwardedAt(\DateTimeImmutable $awardedAt): static
    {
        $this->awardedAt = $awardedAt;

        return $this;
    }
}
