<?php

namespace Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
     public function getEntity() : User 
    {
        return (new User())
       ->setPassword('12345678')
       ->setUsername('Mathilde')
       ->setEmail('mathilde@gmail.com');
    }

    public function assertHasErrors(User $code, int $number) 
    {
        self::bootKernel();
        $validator = self::$container->get('validator');
        $errors = $validator->validate($code);
        $this->assertCount($number,$errors);
        return $errors;
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidBlankPasswordEntity() 
    {
        $errors =  $this->assertHasErrors($this->getEntity()->setPassword(''), 2);
        $this->assertEquals("Veuillez saisir un mot de passe.", $errors[0]->getMessage());
    }

    public function testInvalidLengthPasswordEntity() 
    {
        $errors = $this->assertHasErrors($this->getEntity()->setPassword('123'), 1);
        $this->assertEquals("Le mot de passe doit faire au minimum 8 caractères", $errors[0]->getMessage());
    }
    
    public function testInvalidBlankUsernameEntity() 
    {
       $errors =  $this->assertHasErrors($this->getEntity()->setUsername(''), 1);
       $this->assertEquals("Veuillez saisir un nom d'utilisateur", $errors[0]->getMessage());
    }
    
    public function testInvalidBlankEmailEntity() 
    {
       $errors =  $this->assertHasErrors($this->getEntity()->setEmail(''), 1);
       $this->assertEquals("Vous devez saisir une adresse email.", $errors[0]->getMessage());
    }   

      public function testInvalidFormatEmailEntity() 
    {
        $errors =$this->assertHasErrors($this->getEntity()->setEmail('Mathilde'), 1);
        $this->assertEquals("Le format de l'adresse n'est pas correcte.", $errors[0]->getMessage());
    } 
}