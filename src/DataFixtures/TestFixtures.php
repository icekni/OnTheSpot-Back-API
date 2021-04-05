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

class TestFixtures extends Fixture implements FixtureGroupInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public static function getGroups(): array
    {
        return ['test'];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        // Creation of the catalog
        for ($i = 1; $i <= 3; $i++) {
            // Creation of a category
            $category = new Category();
            $category->setTitle('Categorie ' . $i)
                ->setPicture('assets/images/categorie-glace.png')
                ->setThumbnail('assets/images/categorie-glace_th.png');

            $manager->persist($category);

            for ($j = 1; $j <= 10; $j++) {
                // Creation of a product
                $product = new Product();
                $product->setName('Produit ' . $j)
                    ->setDescription('Description du produit ' . $j)
                    ->setPicture('assets/images/fusee.png')
                    ->setThumbnail('assets/images/fusee_th.png')
                    ->setPrice($j)
                    ->setAvailability(1)
                    ->setCategory($category);

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

        // Creation of an user, cause we need at least one
        $user = new User();
        $user->setFirstname("User")
            ->setLastname("User")
            ->setEmail("user@mail.com")
            ->setPassword("user")
            ->setTelNumber("0000000000")
            ->setRoles(["ROLE_USER"]);

        $manager->persist($user);

        // Creation of all the delivery points
        for ($i = 1; $i <= 10; $i++) {
        // foreach ($meetingPoints as $cityName => $deliveryPoints) {
            // Creation of a delivery point
            $deliveryPoint = new DeliveryPoint();
            $deliveryPoint->setName('Point de retrait ' . $i)
                ->setCity('Ville du point de retrait ' . $i)
                ->setDescription('Description du point de retrait ' . $i)
                ->setLocation('43.599928389547934, -1.4724902051007294');

            $manager->persist($deliveryPoint);
        }

        $manager->flush();
    }
}
