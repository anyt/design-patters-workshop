<?php

namespace App\Entity;

use App\Repository\EnrollmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnrollmentRepository::class)]
class Enrollment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $enrolledAt = null;

    #[ORM\ManyToOne(inversedBy: 'enrollments')]
    private ?Student $student = null;

    #[ORM\ManyToOne(inversedBy: 'enrollments')]
    private ?Course $course = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnrolledAt(): ?\DateTimeImmutable
    {
        return $this->enrolledAt;
    }

    public function setEnrolledAt(\DateTimeImmutable $enrolledAt): static
    {
        $this->enrolledAt = $enrolledAt;

        return $this;
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

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): static
    {
        $this->course = $course;

        return $this;
    }
}
