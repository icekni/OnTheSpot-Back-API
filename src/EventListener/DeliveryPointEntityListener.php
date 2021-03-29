<?php

namespace App\EventListener;

use App\Entity\DeliveryPoint;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class DeliveryPointEntityListener
{
    public function update(DeliveryPoint $deliveryPoint, LifecycleEventArgs $event)
    {
        $deliveryPoint->setUpdatedAt(new \datetime());
    }
}