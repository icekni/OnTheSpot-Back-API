<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserEntityListener
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function prePersist(User $user, LifecycleEventArgs $event)
    {
        $user->setCreatedAt(new \DateTime())
            ->setPassword($this->encoder->encodePassword($user, $user->getPassword()))
        ;
    }

    public function update(User $user, LifecycleEventArgs $event)
    {
        $user->setUpdatedAt(new \datetime());
    }
}