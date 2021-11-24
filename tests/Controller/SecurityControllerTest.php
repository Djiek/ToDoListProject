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
        $this->assertSame(200, $client->getResponse()->getStatusCode());
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
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('marine');
        $client->loginUser($testUser);
        $client->request('GET', '/');  
        $this->assertSelectorTextContains('em#logout', 'Se déconnecter');  
    }
}
