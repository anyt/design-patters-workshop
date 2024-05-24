<?php

namespace Gamification\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gamification\Repository\LeaderboardRepository;

#[ORM\Entity(repositoryClass: LeaderboardRepository::class)]
class Leaderboard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, LeaderboardEntry>
     */
    #[ORM\OneToMany(targetEntity: LeaderboardEntry::class, mappedBy: 'leaderboard')]
    private Collection $leaderboardEntries;

    public function __construct()
    {
        $this->leaderboardEntries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, LeaderboardEntry>
     */
    public function getLeaderboardEntries(): Collection
    {
        return $this->leaderboardEntries;
    }

    public function addLeaderboardEntry(LeaderboardEntry $leaderboardEntry): static
    {
        if (!$this->leaderboardEntries->contains($leaderboardEntry)) {
            $this->leaderboardEntries->add($leaderboardEntry);
            $leaderboardEntry->setLeaderboard($this);
        }

        return $this;
    }

    public function removeLeaderboardEntry(LeaderboardEntry $leaderboardEntry): static
    {
        if ($this->leaderboardEntries->removeElement($leaderboardEntry)) {
            // set the owning side to null (unless already changed)
            if ($leaderboardEntry->getLeaderboard() === $this) {
                $leaderboardEntry->setLeaderboard(null);
            }
        }

        return $this;
    }
}
