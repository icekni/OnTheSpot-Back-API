<?php

namespace App\EventListener;

use App\Entity\DeliveryPoint;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class DeliveryPointEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function updateDeliveryPoint(DeliveryPoint $deliveryPoint, LifecycleEventArgs $event)
    {
        // We need to find the city matching this location
        // First we need to explode location to have real latitude and longitude
        $coord = explode(', ', $deliveryPoint->getLocation());
        // Call to an gouv API
        $result = file_get_contents('https://api-adresse.data.gouv.fr/reverse/?lon=' . $coord[1] . '&lat=' . $coord[0] . '');
        $deliveryPoint->setCity(
            json_decode($result)
                ->features[0]
                ->properties
                ->city
        );
    }


}