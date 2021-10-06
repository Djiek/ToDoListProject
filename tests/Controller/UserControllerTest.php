<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    // public function testHttpOk(): void
    // {
    //     $client = static::createClient();
    //     $client->request('GET', '/users');
    //     $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    // }

    // public function testPageIsRestricted()
    // {
    //     $client = static::createClient();
    //     $client->request('GET', '/users');
    //     $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    // }

    public function testRedirectToLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/users');
        $this->assertResponseRedirects('/login');
    }
}
