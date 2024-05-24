<?php

namespace Gamification\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gamification\Repository\BadgeRepository;

#[ORM\Entity(repositoryClass: BadgeRepository::class)]
class Badge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column]
    private ?int $points = null;

    /**
     * @var Collection<int, Achievement>
     */
    #[ORM\OneToMany(targetEntity: Achievement::class, mappedBy: 'badge')]
    private Collection $achievements;

    /**
     * @var Collection<int, StudentBadge>
     */
    #[ORM\OneToMany(targetEntity: StudentBadge::class, mappedBy: 'badge')]
    private Collection $studentBadges;

    public function __construct()
    {
        $this->achievements = new ArrayCollection();
        $this->studentBadges = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;

        return $this;
    }

    /**
     * @return Collection<int, Achievement>
     */
    public function getAchievements(): Collection
    {
        return $this->achievements;
    }

    public function addAchievement(Achievement $achievement): static
    {
        if (!$this->achievements->contains($achievement)) {
            $this->achievements->add($achievement);
            $achievement->setBadge($this);
        }

        return $this;
    }

    public function removeAchievement(Achievement $achievement): static
    {
        if ($this->achievements->removeElement($achievement)) {
            // set the owning side to null (unless already changed)
            if ($achievement->getBadge() === $this) {
                $achievement->setBadge(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StudentBadge>
     */
    public function getStudentBadges(): Collection
    {
        return $this->studentBadges;
    }

    public function addStudentBadge(StudentBadge $studentBadge): static
    {
        if (!$this->studentBadges->contains($studentBadge)) {
            $this->studentBadges->add($studentBadge);
            $studentBadge->setBadge($this);
        }

        return $this;
    }

    public function removeStudentBadge(StudentBadge $studentBadge): static
    {
        if ($this->studentBadges->removeElement($studentBadge)) {
            // set the owning side to null (unless already changed)
            if ($studentBadge->getBadge() === $this) {
                $studentBadge->setBadge(null);
            }
        }

        return $this;
    }
}
