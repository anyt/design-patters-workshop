<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Enrollment;
use App\Entity\Fee;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Gamification\Entity\Badge;
use Gamification\Entity\CourseAchievement;
use Gamification\Entity\Leaderboard;
use Gamification\Entity\LeaderboardEntry;
use Gamification\Entity\StudentBadge;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class LMSController extends AbstractController
{
    #[Route('/', name: 'enroll_student')]
    public function enrollStudent(
        Request $request,
        EntityManagerInterface $em,
        MailerInterface $mailer,
        LoggerInterface $logger
    ) {
        $studentId = $request->get('student_id');
        $courseId = $request->get('course_id');
        $studentRepository = $em->getRepository(Student::class);
        $courseRepository = $em->getRepository(Course::class);
        $enrollmentRepository = $em->getRepository(Enrollment::class);

        // Fetch student
        $student = $studentRepository->find($studentId);
        if (!$student) {
            return new Response('Student not found', 404);
        }

        // Fetch course
        $course = $courseRepository->find($courseId);
        if (!$course) {
            return new Response('Course not found', 404);
        }

        // Check for existing enrollment
        $existingEnrollment = $enrollmentRepository->findOneBy(['student' => $student, 'course' => $course]);
        if ($existingEnrollment) {
            return new Response('Student already enrolled in this course', 400);
        }

        // Validate course capacity
        $enrollmentsCount = $enrollmentRepository->count(['course' => $course]);
        if ($enrollmentsCount >= $course->getCapacity()) {
            return new Response('Course is full', 400);
        }

        // Validate student's pre-requisites
        $prerequisites = $course->getPrerequisites();
        foreach ($prerequisites as $prerequisite) {
            $prerequisiteEnrollment = $enrollmentRepository->findOneBy(
                ['student' => $student, 'course' => $prerequisite]
            );
            if (!$prerequisiteEnrollment) {
                return new Response('Student has not completed all prerequisites', 400);
            }
        }

        // Enroll student in the course
        $enrollment = new Enrollment();
        $enrollment->setStudent($student);
        $enrollment->setCourse($course);
        $enrollment->setEnrolledAt(new \DateTimeImmutable());
        $em->persist($enrollment);
        $em->flush();

        // Send confirmation email
        $email = $student->getEmail();
        $courseName = $course->getName();
        $email = (new Email())
            ->from('admin@lms.com')
            ->to($email) // Replace with the recipient's email address
            ->subject('Course Enrollment')
            ->html(
                $this->renderView(
                    'emails/enrollment.html.twig',
                    ['course' => $courseName, 'student' => $student->getName()]
                )
            );

        $mailer->send($email);

        // Gamification

        // Assign badges based on course enrollment
        $badgeRepository = $em->getRepository(Badge::class);
        $studentBadgeRepository = $em->getRepository(StudentBadge::class);
        $enrollmentBadges = $badgeRepository->findBy(['name' => 'Enrolled']);
        foreach ($enrollmentBadges as $badge) {
            $studentBadge = new StudentBadge();
            $studentBadge->setStudent($student);
            $studentBadge->setBadge($badge);
            $studentBadge->setAwardedAt(new \DateTimeImmutable());
            $em->persist($studentBadge);
        }

        // Check and award achievements
        $courseAchievementRepository = $em->getRepository(CourseAchievement::class);
        $achievements = $courseAchievementRepository->findBy(['course' => $course]);
        foreach ($achievements as $courseAchievement) {
            $achievement = $courseAchievement->getAchievement();
            if ($courseAchievement->isRequired()) {
                $studentAchievement = $studentBadgeRepository->findOneBy(
                    ['student' => $student, 'badge' => $achievement->getBadge()]
                );
                if (!$studentAchievement) {
                    $studentAchievement = new StudentBadge();
                    $studentAchievement->setStudent($student);
                    $studentAchievement->setBadge($achievement->getBadge());
                    $studentAchievement->setAwardedAt(new \DateTimeImmutable());
                    $em->persist($studentAchievement);
                }
            }
        }

        // Update leaderboard
        $leaderboardRepository = $em->getRepository(Leaderboard::class);
        $leaderboardEntryRepository = $em->getRepository(LeaderboardEntry::class);
        $leaderboards = $leaderboardRepository->findAll();
        foreach ($leaderboards as $leaderboard) {
            $entry = $leaderboardEntryRepository->findOneBy(['student' => $student, 'leaderboard' => $leaderboard]);
            if (!$entry) {
                $entry = new LeaderboardEntry();
                $entry->setStudent($student);
                $entry->setLeaderboard($leaderboard);
                $entry->setScore(0);
                $entry->setRank(0);
                $entry->setCreatedAt(new \DateTimeImmutable());
                $em->persist($entry);
            }
        }

        // Update leaderboard ranks
        foreach ($leaderboards as $leaderboard) {
            $entries = $leaderboardEntryRepository->findBy(['leaderboard' => $leaderboard], ['score' => 'DESC']);
            $rank = 1;
            foreach ($entries as $entry) {
                $entry->setRank($rank);
                $rank++;
                $em->persist($entry);
            }
        }

        // Log enrollment activity
        $logger->info('Student enrolled', [
            'student_id' => $studentId,
            'course_id' => $courseId,
            'timestamp' => (new \DateTimeImmutable())->format('Y-m-d H:i:s')
        ]);

        // Update course popularity metrics
        $course->setPopularity($course->getPopularity() + 1);
        $em->flush();

        // Check for any pending notifications
        $notifications = $student->getNotifications();
        foreach ($notifications as $notification) {
            // Process each notification
            $notification->setProcessedAt(new \DateTimeImmutable());
        }

        // Additional validation for student account status
        if (!$student->isActive()) {
            return new Response('Student account is inactive', 403);
        }

        // Check if the student has any pending fees
        $feeRepository = $em->getRepository(Fee::class);
        $pendingFees = $feeRepository->findBy(['student' => $student, 'status' => 'pending']);
        if (!empty($pendingFees)) {
            return new Response('Student has pending fees', 403);
        }

        // Final confirmation message
        $this->addFlash('success', 'Student enrolled successfully');
        return new Response('Student enrolled successfully', 200);
    }
}
