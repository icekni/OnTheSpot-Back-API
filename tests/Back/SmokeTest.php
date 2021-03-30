<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
{
    /**
     * @dataProvider urlSuccessfullAsAnonymousProvider
     */
    public function testSuccessfullAsAnonymous($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertResponseIsSuccessful();
    }

    public function urlSuccessfullAsAnonymousProvider()
    {
        yield ['/login'];
        // yield ['/confirm/64dfg5h46'];
    }

    /**
     * @dataProvider urlDeniedAsAnonymousProvider
     */
    public function testDeniedAsAnonymous($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertResponseStatusCodeSame(302);
    }

    public function urlDeniedAsAnonymousProvider()
    {
        yield ['/'];
        yield ['/product/'];
        yield ['/order/'];
        yield ['/category/'];
        yield ['/deliverypoint/'];
    }

    /**
     * @dataProvider urlDeniedAsUserProvider
     */
    public function testDeniedAsUser($url)
    {
        $client = self::createClient();

        // Login as user
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('user@mail.com');
        $client->loginUser($user);

        $client->request('GET', $url);

        $this->assertResponseStatusCodeSame(403);
    }

    public function urlDeniedAsUserProvider()
    {
        yield ['/'];
        yield ['/product/'];
        yield ['/order/'];
        yield ['/category/'];
        yield ['/deliverypoint/'];
    }

    /**
     * @dataProvider urlSuccessfullAsAdminProvider
     */
    public function testSuccessfullAsAdmin($url)
    {
        $client = self::createClient();

        // Login as admin
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('admin@mail.com');
        $client->loginUser($user);

        $client->request('GET', $url);

        $this->assertResponseIsSuccessful();
    }

    public function urlSuccessfullAsAdminProvider()
    {
        yield ['/'];
        yield ['/product/'];
        yield ['/order/'];
        yield ['/category/'];
        yield ['/deliverypoint/'];
    }
}