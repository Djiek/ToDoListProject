<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;

class TaskControllerTest extends WebTestCase
{
    public function testRequiredRoleUser()
        {
            $client = static::createClient();
            $userRepository = static::$container->get(UserRepository::class); 
            $testUser = $userRepository->findOneByUsername('marine');   
            $client->loginUser($testUser);
            $client->request('GET', '/tasks');
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        }

    public function testTasksList()
        {
            $client = static::createClient();
            $userRepository = static::$container->get(UserRepository::class); 
            $testUser = $userRepository->findOneByUsername('marine');   
            $client->loginUser($testUser);
            $client->request('GET', '/tasks');
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
            $this->assertSelectorTextContains('a#listTask', 'Créer une tâche');  
        }

    public function testCreateTasks()
        {
            $client = static::createClient();
            $userRepository = static::$container->get(UserRepository::class); 
            $testUser = $userRepository->findOneByUsername('marine');   
            $client->loginUser($testUser);
            $client->request('GET', '/tasks/create');
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
            $this->assertSelectorTextContains('button#createTask', 'Ajouter');  
        } 

    public function testAddTask()
        {
            $client = static::createClient();
            $userRepository = static::$container->get(UserRepository::class); 
            $testUser = $userRepository->findOneByUsername('marine');   
            $client->loginUser($testUser);
            $crawler = $client->request('GET', '/tasks/create');
              $form = $crawler->selectButton('Ajouter')->form([
                'task[title]' => 'Compte1',
                'task[content]' => 'Un contenue de 20 cractères minimum'
            ]);
            $client->submit($form);
            $this->assertResponseRedirects('/tasks');
            $client->followRedirect();
            $this->assertSelectorExists('.alert.alert-success'); 
        } 

    public function testNoDeleteAnonymeTask() 
        {
          $client = static::createClient();
          $userRepository = static::$container->get(UserRepository::class); 
          $testUser = $userRepository->findOneByUsername('marine'); 
          $client->loginUser($testUser);
          $client->followRedirects(false);
          $client->request('DELETE', '/tasks/10/delete');
          $this->assertResponseStatusCodeSame(403);
        } 

    public function testDeleteTasks()
      {
          $client = static::createClient();
          $userRepository = static::$container->get(UserRepository::class); 
          $testUser = $userRepository->findOneByUsername('marine');   
          $client->loginUser($testUser);
          $client->followRedirects(true);
          $response = $client->request('DELETE', '/tasks/5/delete');
          $this->assertResponseIsSuccessful();
      }  

    public function testCheckTasks()
      {
          $client = static::createClient();
          $userRepository = static::$container->get(UserRepository::class); 
          $testUser = $userRepository->findOneByUsername('marine');   
          $client->loginUser($testUser);
          $client->request('GET', '/tasks/3/toggle'); 
          $this->assertResponseStatusCodeSame(302); 
      }    

    public function testUpdateTasks()
      {
          $client = static::createClient();
          $userRepository = static::$container->get(UserRepository::class); 
          $testUser = $userRepository->findOneByUsername('marine');   
          $client->loginUser($testUser);
          $client->request('GET', '/tasks/3/edit');
          $this->assertResponseStatusCodeSame(Response::HTTP_OK);
          $this->assertSelectorTextContains('button#updateTask', 'Modifier');  
      } 

    public function testUpdatedTask()
        {
            $client = static::createClient();
            $userRepository = static::$container->get(UserRepository::class); 
            $testUser = $userRepository->findOneByUsername('marine');   
            $client->loginUser($testUser);
            $crawler = $client->request('GET', '/tasks/3/edit');
            $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'Compte1',
            'task[content]' => 'Un contenue de 20 cractères minimum'
            ]);
            $client->submit($form);
            $this->assertResponseRedirects('/tasks');
            $client->followRedirect();
            $this->assertSelectorExists('.alert.alert-success');
        } 

    public function testNoConnectedUserCreateTask()
        {
            $client = static::createClient();
            $client->request('GET', '/tasks/create');
            $this->assertResponseRedirects('http://localhost/login'); 
            $this->assertResponseStatusCodeSame(302);
        }  

     public function testNoConnectedUserListTask()
        {
            $client = static::createClient();
            $client->request('GET', '/tasks');
            $this->assertResponseRedirects('http://localhost/login'); 
            $this->assertResponseStatusCodeSame(302);
        } 

     public function testNoConnectedUserEditeTask()
        {
            $client = static::createClient();
            $client->request('GET', '/tasks/3/edit');
            $this->assertResponseRedirects('http://localhost/login'); 
            $this->assertResponseStatusCodeSame(302);
        } 

      public function testNoConnectedUserDeleteTask()
        {
            $client = static::createClient();
            $client->request('GET', '/tasks/3/delete');
            $this->assertResponseRedirects('http://localhost/login'); 
            $this->assertResponseStatusCodeSame(302);
        } 

      public function testNoConnectedUserToogleTask()
        {
            $client = static::createClient();
            $client->request('GET', '/tasks/3/toggle');
            $this->assertResponseRedirects('http://localhost/login'); 
            $this->assertResponseStatusCodeSame(302);
        } 
}