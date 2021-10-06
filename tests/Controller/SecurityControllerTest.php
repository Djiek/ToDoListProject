<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{

    public function testVisitingWhileLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        //ça
            $this->assertSame(200, $client->getResponse()->getStatusCode());

//ou ça
    //     static::assertEquals(
    //     Response::HTTP_OK,
    //     $client->getResponse()->getStatusCode()
    // );

//ou encore ça
        //$this->assertResponseStatusCodeSame(Response::HTTP_OK);


        $this->assertSelectorNotExists('.alert.alert-danger');
    }

       public function testLoginWithBadCredentials() 
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'Compte1',
            '_password' => 'fakepassword'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('http://localhost/login');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testSuccessfullLogin() 
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
         $userRepository = static::$container->get(UserRepository::class);
          $testUser = $userRepository->findOneByUsername('marine');

        $client->loginUser($testUser);
        // $form = $crawler->selectButton('Se connecter')->form([
        //     '_username' => 'marine',
        //     '_password' => '123'
        // ]);
        //$client->submit($form);
        //$this->assertResponseRedirects('http://localhost/login');
       // $client->followRedirect();
       $client->request('GET', '/');
    }

  
}
