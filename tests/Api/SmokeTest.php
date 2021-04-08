<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
{
    /**
     * @dataProvider urlSuccessfullAsAnonymousInGetProvider
     */
    public function testSuccessfullAsAnonymousInGet($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertResponseIsSuccessful();
    }

    public function urlSuccessfullAsAnonymousInGetProvider()
    {
        yield ['/api/categories']; // Browse all categories
        yield ['/api/categories/1']; // Browse all product from category 1
        yield ['/api/products']; // Browse all products
        yield ['/api/products/1']; // Read product 1
        yield ['/api/delivery-points']; // Browse all delivery points
    }

    /**
     * @dataProvider urlDeniedAsAnonymousInGetProvider
     */
    public function testDeniedAsAnonymousInGet($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertResponseStatusCodeSame(401);
    }

    public function urlDeniedAsAnonymousInGetProvider()
    {
        yield ['/api/orders']; // Browse all orders from the connected user
        yield ['/api/orders/1']; // Read order 1 from the connected user
        // yield ['/api/users']; // Read all informations about the connected user
    }

    /**
     * @dataProvider urlDeniedAsAnonymousInPostProvider
     */
    public function testDeniedAsAnonymousInPost($url)
    {
        $client = self::createClient();
        $client->request('POST', $url);

        $this->assertResponseStatusCodeSame(401);
    }

    public function urlDeniedAsAnonymousInPostProvider()
    {
        yield ['/api/orders']; // Send an order
    }

    /**
     * @dataProvider urlDeniedAsAnonymousInDeleteProvider
     */
    public function testDeniedAsAnonymousInDelete($url)
    {
        $client = self::createClient();
        $client->request('DELETE', $url);

        $this->assertResponseStatusCodeSame(401);
    }

    public function urlDeniedAsAnonymousInDeleteProvider()
    {
        yield ['/api/users']; // Delete the connected user
    }
}