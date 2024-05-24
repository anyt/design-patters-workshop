<?php

namespace Gamification\Entity;

use App\Entity\Student;
use Doctrine\ORM\Mapping as ORM;
use Gamification\Repository\LeaderboardEntryRepository;

#[ORM\Entity(repositoryClass: LeaderboardEntryRepository::class)]
class LeaderboardEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'leaderboardEntries')]
    private ?Student $student = null;

    #[ORM\ManyToOne(inversedBy: 'leaderboardEntries')]
    private ?Leaderboard $leaderboard = null;

    #[ORM\Column]
    private ?int $score = null;

    #[ORM\Column]
    private ?int $rank = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

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

    public function getLeaderboard(): ?Leaderboard
    {
        return $this->leaderboard;
    }

    public function setLeaderboard(?Leaderboard $leaderboard): static
    {
        $this->leaderboard = $leaderboard;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(int $rank): static
    {
        $this->rank = $rank;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
