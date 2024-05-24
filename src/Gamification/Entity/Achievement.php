<?php

namespace Gamification\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gamification\Repository\AchievementRepository;

#[ORM\Entity(repositoryClass: AchievementRepository::class)]
class Achievement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'achievements')]
    private ?Badge $badge = null;

    #[ORM\Column]
    private ?int $points = null;

    /**
     * @var Collection<int, CourseAchievement>
     */
    #[ORM\OneToMany(targetEntity: CourseAchievement::class, mappedBy: 'achievement')]
    private Collection $courseAchievements;

    public function __construct()
    {
        $this->courseAchievements = new ArrayCollection();
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

    public function getBadge(): ?Badge
    {
        return $this->badge;
    }

    public function setBadge(?Badge $badge): static
    {
        $this->badge = $badge;

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
     * @return Collection<int, CourseAchievement>
     */
    public function getCourseAchievements(): Collection
    {
        return $this->courseAchievements;
    }

    public function addCourseAchievement(CourseAchievement $courseAchievement): static
    {
        if (!$this->courseAchievements->contains($courseAchievement)) {
            $this->courseAchievements->add($courseAchievement);
            $courseAchievement->setAchievement($this);
        }

        return $this;
    }

    public function removeCourseAchievement(CourseAchievement $courseAchievement): static
    {
        if ($this->courseAchievements->removeElement($courseAchievement)) {
            // set the owning side to null (unless already changed)
            if ($courseAchievement->getAchievement() === $this) {
                $courseAchievement->setAchievement(null);
            }
        }

        return $this;
    }
}
