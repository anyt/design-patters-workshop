<?php

namespace App\Services;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Transport\AbstractTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransportFactory;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LoggableEsmtpTransportFactory extends AbstractTransportFactory
{
    public function __construct(
        ?EventDispatcherInterface $dispatcher,
        ?HttpClientInterface $client,
        ?LoggerInterface $logger,
        private EsmtpTransportFactory $esmtpTransportFactory,
    ) {
        parent::__construct($dispatcher, $client, $logger);
    }

    protected function getSupportedSchemes(): array
    {
        return $this->esmtpTransportFactory->getSupportedSchemes();
    }

    public function create(Dsn $dsn): TransportInterface
    {
        $transport = $this->esmtpTransportFactory->create($dsn);

        return new LoggableTransport($transport->getStream(), $this->dispatcher, $this->logger);
    }
}
