<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\OrderProduct;
use App\Entity\DeliveryPoint;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // We use the fixtures in the dev version of the application to avoid having to load them on each developer's computer

        // Filling of the database with the data given by the product owner 
        $catalog = [
            "Glaces" => [
                "products" => [
                    "Magnum Vanille" => [
                        "picture" => "magnum-vanille"
                    ],
                    "Magnum Chocolat" => [
                        "picture" => "magnum-chocolat"
                    ],
                    "Magnum Amande" => [
                        "picture" => "magnum-amande"
                    ],
                    "Cornet Vanille" => [
                        "picture" => "cornet-vanille"
                    ],
                    "Cornet Chocolat" => [
                        "picture" => "cornet-chocolat"
                    ],
                    "Cornet Vanille-Chocolat" => [
                        "picture" => "cornet-vanille-chocolat"
                    ],
                    "Calippo Citron" => [
                        "picture" => "calipo-citron"
                    ],
                    "Fusée" => [
                        "picture" => "fusee"
                    ],
                    "Glace en pot" => [
                        "picture" => "glace-pot"
                    ],
                ],
                "picture" => "categorie-glace",
            ],
            "Snack" => [
                "products" => [
                    "Beignet Pomme" => [
                        "picture" => "beignet-pomme"
                    ],
                    "Beignet Chocolat-Noisette" => [
                        "picture" => "beignet-choconoisette"
                    ],
                    "Beignet Sucre" => [
                        "picture" => "beignet-sucre"
                    ],
                    "Chouchou/pralines" => [
                        "picture" => "praline"
                    ],
                ],
                "picture" => "categorie-snack",
            ],
            "Boissons" => [
                "products" => [
                    "Coca Cola 33cL" => [
                        "picture" => "can-coca"
                    ],
                    "Orangina 33cL" => [
                        "picture" => "can-orangina"
                    ],
                    "Sprite 33cL" => [
                        "picture" => "can-sprite"
                    ],
                    "Fanta 33cL" => [
                        "picture" => "can-fanta"
                    ],
                    "Pepsi 33cL" => [
                        "picture" => "can-pepsi"
                    ],
                    "Perrier 33cL" => [
                        "picture" => "can-perrier"
                    ],
                    "Vittel 50cL" => [
                        "picture" => "bouteille-eau"
                    ],
                ],
                "picture" => "categorie-boisson",
            ],
        ];

        $meetingPoints = [
            "Seignosses" => [
                "Plage des Casernes" => "43.72361165179431, -1.432630019417613",
                "Plage de Lagreou" => "43.712451317216235, -1.4350826442892204",
                "Plage du Penon" => "43.70953930898296, -1.4366136613003513",
                "Plage des Bourdaines-Nord" => "43.69785017286851, -1.4392117801825937",
                "Plage des Bourdaines-Sud" => "43.69461227541181, -1.4398114441658492",
                "Plage des Estagnots" => "43.68740581040465, -1.4408497733984424",
            ],
            "Hossegor" => [
                "Plage des culs Nuls" => "43.680159017323774, -1.4405783656418432",
                "Plage Hossegor La Nord" => "43.673992938118744, -1.4408603808962261",
                "Plage Hossegor la Centrale" => "43.66783271771156, -1.4417445211966518",
                "Plage Hossegor La Sud" => "43.66191141632739, -1.4442080885322892",
                "Plage Hossegor Notre Dame" => "43.659953393680404, -1.4454350587472817",
            ],
            "Cap Breton" => [
                "Plage de l'Estacade" => "43.65486900205929, -1.4454652786890418",
                "Plage du Prévent" => "43.65002695464368, -1.44611033054185",
                "Plage du Santocha" => "43.64654214106528, -1.4452473292332608",
                "Plage de la Piste" => "43.639024694104016, -1.4476847749852522",
                "Plage des Océanides" => "43.63619852971307, -1.4492846813513702",
                "Plage de la Pointe" => "43.62504717766695, -1.4528466549311978",
            ],
            "Labenne" => [
                "Plage Centrale" => "43.599928389547934, -1.4724902051007294",
                "Plage Casanova" => "43.594662130566554, -1.4753794215477962",
            ]
        ];

        // Creation of the catalog
        foreach ($catalog as $categoryName => $categoryDetails) {
            // Creation of a category
            $category = new Category();
            $category->setTitle($categoryName)
                ->setPicture('assets/images/' . $categoryDetails['picture'])
                ->setThumbnail('assets/images/' . $categoryDetails['picture'] . '_th.png');

            $manager->persist($category);

            $productsList = [];
            foreach ($categoryDetails['products'] as $productName => $productDetails) {
                // Creation of a product
                $product = new Product();
                $product->setName($productName)
                    ->setDescription($faker->unique()->sentence())
                    ->setPicture('assets/images/' . $productDetails['picture'] . '.png')
                    ->setThumbnail('assets/images/' . $productDetails['picture'] . '_th.png')
                    ->setPrice(mt_rand(0, 10))
                    ->setAvailability(
                        round(
                            mt_rand(0, 1)
                        )
                    )
                    ->setCategory($category);

                $productsList[] = $product;

                $manager->persist($product);
            }
        }


        // Creation of an admin, cause we need at least one
        $admin = new User();
        $admin->setFirstname("Admin")
            ->setLastname("Admin")
            ->setEmail("admin@mail.com")
            ->setPassword("admin")
            ->setTelNumber("0000000000")
            ->setRoles(["ROLE_ADMIN"]);

        $manager->persist($admin);

        // Creation of an admin, cause we need at least one
        $user = new User();
        $user->setFirstname("User")
            ->setLastname("User")
            ->setEmail("user@mail.com")
            ->setPassword("user")
            ->setTelNumber("0000000000")
            ->setRoles(["ROLE_USER"]);

        $manager->persist($user);

        // Creation of a few random user
        $usersList = [];
        for ($i = 0; $i < 15; $i++) {
            $user = new User();
            $user->setFirstname($faker->unique()->firstname())
                ->setLastname($faker->unique()->lastname())
                ->setEmail($faker->unique()->email())
                ->setPassword("password")
                ->setTelNumber($faker->unique()->phoneNumber())
                ->setRoles(["ROLE_USER"]);

            $usersList[] = $user;

            $manager->persist($user);
        }

        // Creation of all the delivery points
        $deliveryPointsList = [];
        foreach ($meetingPoints as $cityName => $deliveryPoints) {
            foreach ($deliveryPoints as $deliveryPointName => $deliveryPointlocation) {
                // Creation of a delivery point
                $deliveryPoint = new DeliveryPoint();
                $deliveryPoint->setName($deliveryPointName)
                    ->setCity($cityName)
                    ->setDescription($faker->unique()->sentence())
                    ->setLocation($deliveryPointlocation);

                $deliveryPointsList[] = $deliveryPoint;

                $manager->persist($deliveryPoint);
            }
        }

        // fake orders
        for ($i = 0; $i < 50; $i++) {
            $order = new Order();

            $order->setDeliveryTime($faker->dateTimeBetween('-3 weeks', 'now'))
                ->setUser($usersList[array_rand($usersList)])
                ->setDeliveryPoint($deliveryPointsList[array_rand($deliveryPointsList)])
                ->setStatus(3);

            // Add a random amount of product
            for ($j = 0; $j < mt_rand(1, 10); $j++) {
                $orderLine = new OrderProduct();
                $orderLine->setQuantity(mt_rand(1, 5))
                    ->setProduct($productsList[array_rand($productsList)]);

                $order->addOrderProduct($orderLine);
                    
            }

            $manager->persist($order);
        }
        // Fake order with status "en attente"
        for ($i = 0; $i < 10; $i++) {
            $order = new Order();

            $order->setDeliveryTime($faker->dateTimeBetween('now', '+3 hours'))
                ->setUser($usersList[array_rand($usersList)])
                ->setDeliveryPoint($deliveryPointsList[array_rand($deliveryPointsList)])
                ->setStatus(0);

            // Add a random amount of product
            for ($j = 0; $j < mt_rand(1, 10); $j++) {
                $orderLine = new OrderProduct();
                $orderLine->setQuantity(mt_rand(1, 5))
                    ->setProduct($productsList[array_rand($productsList)]);

                $order->addOrderProduct($orderLine);
            }

            $manager->persist($order);
        }

        // Fake order with status "en preparation" or "en livraison"
        for ($i = 0; $i < 50; $i++) {
            $order = new Order();

            $order->setDeliveryTime($faker->dateTimeBetween('now', '+1 hours'))
                ->setUser($usersList[array_rand($usersList)])
                ->setDeliveryPoint($deliveryPointsList[array_rand($deliveryPointsList)])
                ->setStatus(mt_rand(1, 2));

            // Add a random amount of product
            for ($j = 0; $j < mt_rand(1, 10); $j++) {
                $orderLine = new OrderProduct();
                $orderLine->setQuantity(mt_rand(1, 5))
                    ->setProduct($productsList[array_rand($productsList)]);

                $order->addOrderProduct($orderLine);
            }

            $manager->persist($order);
        }

        $manager->flush();
    }
}
