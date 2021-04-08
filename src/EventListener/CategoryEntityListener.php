<?php

namespace App\EventListener;

use App\Entity\Category;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function update(Category $category, LifecycleEventArgs $event)
    {
        $category->setSlug(
            $this->slugger->slug(
                    $category->getTitle()
                )
                ->lower()
            )
            ->setUpdatedAt(new \datetime())
        ;
    }
}