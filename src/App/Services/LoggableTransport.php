<?php

namespace App\Services;

use App\Traits\LoggableToFileTrait;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\Smtp\SmtpTransport;
use Symfony\Component\Mime\RawMessage;

class LoggableTransport extends SmtpTransport
{
    use LoggableToFileTrait;

    public function send(RawMessage $message, ?Envelope $envelope = null): ?SentMessage
    {
        $this->log('Sending email', ['message' => $message->toString()]);
        return parent::send($message, $envelope);
    }
}
