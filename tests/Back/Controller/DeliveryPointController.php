<?php

namespace App\Tests\Back\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DeliveryPointControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        // Login as admin
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('admin@mail.com');
        $client->loginUser($user);

        // Then test product_index
        $client->request('GET', '/deliverypoint/');
        $this->assertSelectorTextContains('title', 'Tous les points de retrait');
    }

    public function testNew()
    {
        $client = static::createClient();

        // Login as admin
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('admin@mail.com');
        $client->loginUser($user);

        // Then test product_new
        $crawler = $client->request('GET', '/deliverypoint/new');
        $this->assertSelectorTextContains('title', 'Nouveau point de retrait');

        // Selection of the form
        $form = $crawler->filter('.btn-primary')->form([
            'delivery_point[location]'    => '43.599928389547934, -1.4724902051007294',
            'delivery_point[name]'    => 'Plage centrale de Labenne',
            'delivery_point[description]'    => 'Viribus mari navem instructam indigenis ipso omnibusque Iovis aedificet crebra altera et insignis duae duae eadem duae Veneris Salamis navem.',
        ], "POST");
        // Submit the form
        $client->submit($form);

        $crawler = $client->followRedirect();
        
        $this->assertSelectorTextContains('.alert', 'Création du point de retrait "Plage centrale de Labenne" effectuée.');
    }

    public function testRead()
    {
        $client = static::createClient();

        // Login as admin
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('admin@mail.com');
        $client->loginUser($user);

        // Then test product_read
        $client->request('GET', '/deliverypoint/1');
        $this->assertSelectorTextContains('title', 'Point de retrait 1');
    }

    public function testEdit()
    {
        $client = static::createClient();

        // Login as admin
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('admin@mail.com');
        $client->loginUser($user);

        // Then test product_new
        $crawler = $client->request('GET', '/deliverypoint/11/edit');
        $this->assertSelectorTextContains('title', 'Modification Plage centrale de Labenne');

        // Selection of the form
        $form = $crawler->filter('.btn-primary')->form([
            'delivery_point[name]'    => 'Plage centrale de Labenne a ordures',
        ], "POST");
        // Submit the form
        $client->submit($form);

        $crawler = $client->followRedirect();
        
        $this->assertSelectorTextContains('.alert', 'Modifications du point de retrait "Plage centrale de Labenne a ordures" enregistrées.');
    }

    public function testDelete()
    {
        
        $client = static::createClient();

        // Login as admin
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('admin@mail.com');
        $client->loginUser($user);

        // go to product_index
        $crawler = $client->request('GET', '/deliverypoint/');

        // The delete button is a form that we need to submit
        $form = $crawler->selectButton('Confirmer')->form();
        // Submit the form
        $client->submit($form);

        $crawler = $client->followRedirect();
        
        $this->assertSelectorTextContains('.alert', 'Suppression du point de retrait "Plage centrale de Labenne a ordures" effectuée.');
    }
}
