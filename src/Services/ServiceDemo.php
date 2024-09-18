<?php

namespace App\Services;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;

readonly class ServiceDemo
{
    public function __construct(
        private LoggerInterface $logger,
        private Security $security,
    ) {
    }

    public function example(): void
    {
        // traitement métier

        $this->logger->debug(sprintf(
            'Le service a été déclenché par %s',
            $this->security->getUser()?->getUserIdentifier() ?? 'anonyme',
        ));
    }
}
