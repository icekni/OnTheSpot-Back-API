<?php

namespace App\Tests\Back\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        // Login as admin
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('admin@mail.com');
        $client->loginUser($user);

        // Then test product_index
        $client->request('GET', '/product/');
        $this->assertSelectorTextContains('title', 'Tous les produits');
    }

    public function testNew()
    {
        $client = static::createClient();

        // Login as admin
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('admin@mail.com');
        $client->loginUser($user);

        // Then test product_new
        $crawler = $client->request('GET', '/product/new');
        $this->assertSelectorTextContains('title', 'Nouveau produit');

        $photo = new UploadedFile(
            'test.png',
            'test.png',
            'image/png',
            null
        );

        // Fill the form
        $client->request(
            'POST',
            '/product/new',
            [
                'product[name]' => 'new product',
                'product[description]' => 'description',
                'product[price]' => '99.95',
                'product[availability]' => '1',
                'product[category]' => '53',
            ],
            [
                'product[picture]' => $photo,
                'product[thumbnail]' => $photo,
            ]
        );

        $this->assertResponseRedirects();
    }
}
