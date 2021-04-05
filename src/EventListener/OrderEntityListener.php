<?php

namespace App\EventListener;

use App\Entity\Order;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class OrderEntityListener
{
    public function update(Order $order, LifecycleEventArgs $event)
    {
        $order->setUpdatedAt(new \datetime());
    }
}