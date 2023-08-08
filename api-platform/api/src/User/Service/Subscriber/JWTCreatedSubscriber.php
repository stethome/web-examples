<?php

declare(strict_types=1);

namespace App\User\Service\Subscriber;

use App\User\Service\AppUserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JWTCreatedSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [Events::JWT_CREATED => 'onJWTCreated'];
    }

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        /** @var AppUserInterface $user */
        $user = $event->getUser();

        $payload = $event->getData();
        $payload['uuid'] = $user->getUuid();

        $event->setData($payload);
    }
}
