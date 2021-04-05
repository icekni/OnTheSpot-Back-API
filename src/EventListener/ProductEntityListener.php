<?php

namespace App\EventListener;

use App\Entity\Product;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function updateProduct(Product $product, LifecycleEventArgs $event)
    {
        $product->setSlug(
            $this->slugger->slug(
                    $product->getName()
                )->lower()
            )
            ->setUpdatedAt(new \datetime())
        ;

        $price = str_replace(",", ".", $product->getPrice());
        $product->setPrice($price);
    }
}
