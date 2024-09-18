<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class LocaleListener
{
    #[AsEventListener(event: KernelEvents::REQUEST, priority: 64)]
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $preferredLocale  = $request->getPreferredLanguage(['fr', 'en', 'en_us']);

        if ($preferredLocale) {
            $request->setLocale($preferredLocale);
        }
    }
}
