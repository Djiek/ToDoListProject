<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;

class UserControllerTest extends WebTestCase
{
    public function testNoConnectedUserPageUsers()
    {
        $client = static::createClient();
        $client->request('GET', '/users');
        $this->assertResponseRedirects('http://localhost/login'); 
        $this->assertResponseStatusCodeSame(302);
    }

    public function testNoConnectedUserEditPage()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/create');
        $this->assertResponseRedirects('http://localhost/login');
        $this->assertResponseStatusCodeSame(302); 
    }

    public function testNoConnectedUserPageAdmin()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/1/edit');
        $this->assertResponseRedirects('http://localhost/login'); 
        $this->assertResponseStatusCodeSame(302);
    }

    public function testNoConnectedUserHomePage()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/create');
        $this->assertResponseRedirects('http://localhost/login'); 
        $this->assertResponseStatusCodeSame(302);
    }

    public function testUsersList()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class); 
        $testUser = $userRepository->findOneByUsername('marine');   
        $client->loginUser($testUser);
        $client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1#userList', 'Liste des utilisateurs');  
    }

    public function testRequiredNoAdminRoleForCreate()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class); 
        $testUser = $userRepository->findOneByUsername('marine');   
        $client->loginUser($testUser);
        $client->request('GET', '/admin/create');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testCreateUser()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class); 
        $testUser = $userRepository->findOneByUsername('marine2');   
        $client->loginUser($testUser);
        $crawler =   $client->request('GET', '/admin/create');
        $form = $crawler->selectButton('Ajouter')->form([
        'user[username]' => 'Paulo222',
        'user[email]' => 'paulo222@gmail.com',
        'user[roles]' => "ROLE_ADMIN",
        'user[password][first]' => "12345678",
        'user[password][second]' => "12345678"
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/users');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    } 
        
    public function testRequiredAdminRoleForCreate()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class); 
        $testUser = $userRepository->findOneByUsername('marine2');   
        $client->loginUser($testUser);
        $client->request('GET', '/admin/create');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('#addCreateUser', 'Ajouter');
        $this->assertSelectorNotExists('.alert.alert-success');
        $this->assertSame(200, $client->getResponse()->getStatusCode()); 
    }

    public function testRequiredNoAdminRoleForEdit()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class); 
        $testUser = $userRepository->findOneByUsername('marine');   
        $client->loginUser($testUser);
        $client->request('GET', '/admin/2/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testRequiredAdminRoleForEdit()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class); 
        $testUser = $userRepository->findOneByUsername('marine2');   
        $client->loginUser($testUser);
        $client->request('GET', '/admin/1/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }   

    public function testUpdatedUser()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class); 
        $testUser = $userRepository->findOneByUsername('marine2');   
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/admin/4/edit');
        $form = $crawler->selectButton('Modifier')->form([
        'user_update[username]' => 'Paulo',
        'user_update[email]' => 'paulo@gmail.com',
        'user_update[roles]' => "ROLE_ADMIN"
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/users');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    } 
}
