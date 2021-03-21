<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');
        
        // We use the fixtures in the dev version of the application to avoid having to load them on each developer's computer

        // Filling of the database with the data given by the product owner 
        $catalog = [
            "Glaces" => [
                "Magnum Vanille",
                "Magnum Chocolat",
                "Magnum Amande",
                "Cornet Vanille",
                "Cornet Chocolat",
                "Calipso Citron",
                "Fusée",
                "Glace en pot",
            ],
            "Snack" => [
                "Beignet Pomme",
                "Beignet Cocholat-Noisette",
                "Beignet Sucre",
                "Chouchou/pralines",
            ],
            "Boissons" => [
                "Coca Cola 33cL",
                "Orangina 33cL",
                "Sprite 33cL",
                "Fanta 33cL",
                "Pepsi 33cL",
                "Perrier 33cL",
                "Vittel 50cL",
            ],
        ];

        $deliveryPoints = [
            "Seignosses" => [
                "Plage des Casernes",
                "Plage de Lagreou",
                "Plage du Penou",
                "Plage des Bourdaines-Nord",
                "Plage des Bourdaines-Sud",
                "Plage des Estagnots",
            ],
            "Hossegor" => [
                "Plage des culs Nuls",
                "Plage Hossegor La Nord",
                "Plage Hossegor la Centrale",
                "Plage Hossegor La Sud",
                "Plage Hossgor Notre Dame",
            ],
            "Cap Breton" => [
                "Plage de l'Estacade",
                "Plage du Prévent",
                "Plage du Santocha",
                "Plage de la Piste",
                "Plage des Océanides",
                "Plage de la Pointe",
            ],
            "Labenne" => [
                "Plage Centrale",
                "Plage Casanova",
            ]
        ];

        //
        foreach ($catalog as $categoryName => $products) {
            // Creation of the category
            $category = new Category();
            $category->setTitle($categoryName);
            
            $manager->persist($category);

            foreach ($products as $productName) {
                $product = new Product();
                $product->setName($productName)
                    ->setDescription($faker->unique()->sentence())
                    ->setPicture("https://baconmockup.com/300/200")
                    ->setPrice(mt_rand(0, 10))
                    ->setAvailability(
                        round(
                            mt_rand(0,1)
                        )
                    )
                    ->setCategory($category)
                ;

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
