<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\City;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\DeliveryPoint;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

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

        $meetingPoints = [
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
                "Plage Hossegor Notre Dame",
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

        // Creation of the catalog
        foreach ($catalog as $categoryName => $products) {
            // Creation of a category
            $category = new Category();
            $category->setTitle($categoryName);
            
            $manager->persist($category);

            foreach ($products as $productName) {
                // Creation of a product
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


        // Creation of an admin, cause we need at least one
        $admin = new User();
        $admin->setFirstname("Admin")
            ->setLastname("Admin")
            ->setEmail("admin@mail.com")
            ->setPassword(
                $this->encoder->encodePassword($admin, "admin")
            )
            ->setTelNumber("0000000000")
            ->setRoles(["ROLE_ADMIN"])
        ;

        $manager->persist($admin);

        // Creation of an admin, cause we need at least one
        $user = new User();
        $user->setFirstname("User")
            ->setLastname("User")
            ->setEmail("user@mail.com")
            ->setPassword(
                $this->encoder->encodePassword($user, "user")
            )
            ->setTelNumber("0000000000")
            ->setRoles(["ROLE_USER"])
        ;

        $manager->persist($user);

        // Creation of all the delivery points
        foreach ($meetingPoints as $cityName => $deliveryPoints) {
            // Creation of a city
            $city = new City();
            $city->setName($cityName);

            $manager->persist($city);

            foreach ($deliveryPoints as $deliveryPointName) {
                // Creation of a delivery point
                $deliveryPoint = new DeliveryPoint();
                $deliveryPoint->setName($deliveryPointName)
                    ->setCity($city)
                    ->setDescription($faker->unique()->sentence())
                    ->setLocation(
                        $faker->unique()->latitude() . 
                        ", " . 
                        $faker->unique()->longitude())
                ;

                $manager->persist($deliveryPoint);
            }
        }

        // TODO : Creer des fausses commandes, associées à des faux utilisateurs

        $manager->flush();
    }
}
