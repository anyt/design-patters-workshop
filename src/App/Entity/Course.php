<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gamification\Entity\CourseAchievement;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $capacity = null;

    #[ORM\Column]
    private ?int $popularity = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'courses')]
    private Collection $prerequisites;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'prerequisites')]
    private Collection $courses;

    /**
     * @var Collection<int, Enrollment>
     */
    #[ORM\OneToMany(targetEntity: Enrollment::class, mappedBy: 'course')]
    private Collection $enrollments;

    /**
     * @var Collection<int, CourseAchievement>
     */
    #[ORM\OneToMany(targetEntity: CourseAchievement::class, mappedBy: 'course')]
    private Collection $courseAchievements;

    public function __construct()
    {
        $this->prerequisites = new ArrayCollection();
        $this->courses = new ArrayCollection();
        $this->enrollments = new ArrayCollection();
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

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getPopularity(): ?int
    {
        return $this->popularity;
    }

    public function setPopularity(int $popularity): static
    {
        $this->popularity = $popularity;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getPrerequisites(): Collection
    {
        return $this->prerequisites;
    }

    public function addPrerequisite(self $prerequisite): static
    {
        if (!$this->prerequisites->contains($prerequisite)) {
            $this->prerequisites->add($prerequisite);
        }

        return $this;
    }

    public function removePrerequisite(self $prerequisite): static
    {
        $this->prerequisites->removeElement($prerequisite);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(self $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->addPrerequisite($this);
        }

        return $this;
    }

    public function removeCourse(self $course): static
    {
        if ($this->courses->removeElement($course)) {
            $course->removePrerequisite($this);
        }

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
            $enrollment->setCourse($this);
        }

        return $this;
    }

    public function removeEnrollment(Enrollment $enrollment): static
    {
        if ($this->enrollments->removeElement($enrollment)) {
            // set the owning side to null (unless already changed)
            if ($enrollment->getCourse() === $this) {
                $enrollment->setCourse(null);
            }
        }

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
            $courseAchievement->setCourse($this);
        }

        return $this;
    }

    public function removeCourseAchievement(CourseAchievement $courseAchievement): static
    {
        if ($this->courseAchievements->removeElement($courseAchievement)) {
            // set the owning side to null (unless already changed)
            if ($courseAchievement->getCourse() === $this) {
                $courseAchievement->setCourse(null);
            }
        }

        return $this;
    }
}
