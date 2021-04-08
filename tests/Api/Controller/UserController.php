<?php

namespace App\Tests\Api\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    // public function testRegistration()
    // {
    //     $client = self::createClient();

    //     $crawler = $client->xmlHttpRequest('POST', '/api/users', [
    //         "firstname" => "Gerfard",
    //         "lastname" => "Menvuca",
    //         'email' => 'gerard@mail.com',
    //         'telNumber' => '0000000000',
    //         'password' => 'gerard',
    //     ]);
    //     var_dump($crawler->);

    //     // $this->assertResponseStatusCodeSame(201);
    // }

    // public function testLogin()
    // {
    //     $client = self::createClient();
    //     $crawler = $client->xmlHttpRequest('POST', '/api/login', [
    //         'username' => 'gerard@mail.com',
    //         'password' => 'gerard',
    //     ]);

    //     var_dump($crawler);

    //     // $this->assertResponseStatusCodeSame(200);
    // }

    // public function testDelete()
    // {
    //     $client = self::createClient();
    //     $client->xmlHttpRequest('DELETE', '/api/users', [
    //         'username' => 'gerard@mail.com',
    //         'password' => 'gerard',
    //     ]);

    //     $this->assertResponseStatusCodeSame(200);
    // }
}
