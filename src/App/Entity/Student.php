<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gamification\Entity\LeaderboardEntry;
use Gamification\Entity\StudentBadge;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    /**
     * @var Collection<int, Enrollment>
     */
    #[ORM\OneToMany(targetEntity: Enrollment::class, mappedBy: 'student')]
    private Collection $enrollments;

    /**
     * @var Collection<int, Fee>
     */
    #[ORM\OneToMany(targetEntity: Fee::class, mappedBy: 'student')]
    private Collection $fees;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'student', orphanRemoval: true)]
    private Collection $notifications;

    /**
     * @var Collection<int, LeaderboardEntry>
     */
    #[ORM\OneToMany(targetEntity: LeaderboardEntry::class, mappedBy: 'student')]
    private Collection $leaderboardEntries;

    /**
     * @var Collection<int, StudentBadge>
     */
    #[ORM\OneToMany(targetEntity: StudentBadge::class, mappedBy: 'student')]
    private Collection $studentBadges;

    public function __construct()
    {
        $this->enrollments = new ArrayCollection();
        $this->fees = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->leaderboardEntries = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection<int, Enrollment>
     */
    public function getEnrollments(): Collection
    {
        return $this->enrollments;
    }

    public function addEnrollment(Enrollment $enrollment): static
    {
        if (!$this->enrollments->contains($enrollment)) {
            $this->enrollments->add($enrollment);
            $enrollment->setStudent($this);
        }

        return $this;
    }

    public function removeEnrollment(Enrollment $enrollment): static
    {
        if ($this->enrollments->removeElement($enrollment)) {
            // set the owning side to null (unless already changed)
            if ($enrollment->getStudent() === $this) {
                $enrollment->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fee>
     */
    public function getFees(): Collection
    {
        return $this->fees;
    }

    public function addFee(Fee $fee): static
    {
        if (!$this->fees->contains($fee)) {
            $this->fees->add($fee);
            $fee->setStudent($this);
        }

        return $this;
    }

    public function removeFee(Fee $fee): static
    {
        if ($this->fees->removeElement($fee)) {
            // set the owning side to null (unless already changed)
            if ($fee->getStudent() === $this) {
                $fee->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setStudent($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getStudent() === $this) {
                $notification->setStudent(null);
            }
        }

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
            $leaderboardEntry->setStudent($this);
        }

        return $this;
    }

    public function removeLeaderboardEntry(LeaderboardEntry $leaderboardEntry): static
    {
        if ($this->leaderboardEntries->removeElement($leaderboardEntry)) {
            // set the owning side to null (unless already changed)
            if ($leaderboardEntry->getStudent() === $this) {
                $leaderboardEntry->setStudent(null);
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
            $studentBadge->setStudent($this);
        }

        return $this;
    }

    public function removeStudentBadge(StudentBadge $studentBadge): static
    {
        if ($this->studentBadges->removeElement($studentBadge)) {
            // set the owning side to null (unless already changed)
            if ($studentBadge->getStudent() === $this) {
                $studentBadge->setStudent(null);
            }
        }

        return $this;
    }
}
