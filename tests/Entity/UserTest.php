<?php

namespace Tests\Entity;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;



class UserTest extends KernelTestCase
{
     public function getEntity() : User 
    {
        return (new User());
    }

    public function assertHasErrors(User $code, int $number) 
    {
        $validator = Validation::createValidator();
        $errors = $validator->validate($code);
        $this->assertCount($number,$errors);
        return $errors;
    }

    public function testValidEntity()
    {
        $this->assertHasErrors(new user, 0);
    }

    public function testInvalidEntity()
    {
        $this->assertHasErrors($this->getEntity()->setPassword('123'), 1); 
    }

    // public function testInvalidBlankPassword() 
    // {
    //     $this->assertHasErrors($this->getEntity()->setPassword(''), 1);
    //     $this->assertEquals("Le mot de passe doit faire au minimum 8 caractères", $errors[0]->getPassword());
    // }
    
    // public function testInvalidBlankUsername() 
    // {
    //    $errors =  $this->assertHasErrors($this->getEntity()->setUsername(''), 1);
     //     $this->assertEquals("le nom que vous avez indiqué est déja utilisé.", $errors[0]->getUsername());
    // }
    
    // public function testInvalidBlankRoles() 
    // {
    //     $this->assertHasErrors($this->getEntity()->setRoles(''), 1);
    // }
    
    // public function testInvalidBlankEmail() 
    // {
    //     $this->assertHasErrors($this->getEntity()->setEmail(''), 1);
    // }

    // public function testInvalidUsedUsername() {
    //     $this->loadFixtureFiles([dirname(__DIR__) . '/fixtures/user_login.yaml']);
    //    $this->assertHasErrors($this->getEntity()->setUsername('Marine'), 1); 
    // }


  
        //  fields= {"username"},
// * message= "le nom que vous avez indiqué est déja utilisé."

// @Assert\Length(min="8",minMessage="Le mot de passe doit faire au minimum 8 caractères")

// * @Assert\NotBlank(message="Vous devez saisir une adresse email.")
 //    * @Assert\Email(message="Le format de l'adresse n'est pas correcte.")
    
}