# Патерни проєктування в PHP: практичне використання

## Оточення
- PHP 8.2
- Docker
- [Symfony Server](https://symfony.com/download)

## Установка
- Склонуйте репозиторій
- Запустіть сервіси за допомогою `docker-compose up -d`
- Встановіть застосунок за допомогою `composer install`
- Запустіть сервер за допомогою `symfony serve -d`
- Відкрийте сторінку в браузері за адресою https://127.0.0.1:8000/?student_id=1&course_id=5.

## Завдання

1. Реалізувати патерн **фабричний метод** для створення об'єктів `Enrollment` з `Student` та `Course`.
1. Створити інтерфейс `EmailFactoryInterface::createEmail(Student $student, Course $course): Email` та реалізувати патерн **абстрактна фабрика** для створення листів у `LMSController`.
1. Створити інтерфейс `EmailSenderInterface::sendEmail(string $to, string $subject, string $body): void` та реалізувати патерн **адаптер** від `Symfony\Component\Mailer\MailerInterface` до нового інтерфейсу.
1. Створити окремий клас замість `App\Traits\LoggableToFileTrait` та реалізувати патерн **декоратор** замість наслідування `App\Services\LoggableTransport`.
1. Реалізувати патерн **фасад** для взаємодії з `Doctrine\ORM\EntityManagerInterface` у `LMSController`.
1. Створити інтерфейс `CourseValidationStrategyInterface::validate(Student $student, Course $course): bool` та реалізувати патерн **стратегія** для валідації курсів у `LMSController`.
1. Створити подію `StudentEnrolledEvent` та реалізувати патерн **спостерігач** для логування, відправки листів та гейміфікації у `LMSController` за допомогою **посередника** (symfony event dispatcher).
1. Створіть інтерфейс `GamificationVisitorInterface::visit(Student $student, Course $course): void`. Використовуючи патерни **відвідувач** та **компонувальник** відрефакторіть логіку гейміфікації.
1. Реалізувати патерн **ланцюжок обов'язків** для валідації реєстрацій студентів у `LMSController`.
