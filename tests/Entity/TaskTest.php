<?php
namespace Tests\Entity;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function getEntity() : Task 
    {
        return (new Task())
        ->setTitle('Un titre')
        ->setContent('Je suis un contenu de 10 caractères minimum')
        ->setCreatedAt(new \DateTimeImmutable())
        ->setIsDone(1);
    }

    public function assertHasErrors(Task $code, int $number) 
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

    public function testInvalidBlankTitleEntity() 
    {
        $errors =   $this->assertHasErrors($this->getEntity()->setTitle(''), 2);
        $this->assertEquals("Veuillez saisir un titre.", $errors[0]->getMessage());
    }

    public function testInvalidLengthTitleEntity() 
    {
        $errors =    $this->assertHasErrors($this->getEntity()->setTitle('Non'), 1);
        $this->assertEquals("Le titre doit faire au minimum 5 caractères", $errors[0]->getMessage());
    }

    public function testInvalidBlankContentEntity() 
    {
        $errors =  $this->assertHasErrors($this->getEntity()->setContent(''), 2);
        $this->assertEquals("Veuillez saisir un contenu.", $errors[0]->getMessage());
    }

    public function testInvalidLengthContentEntity() 
    {
        $errors =  $this->assertHasErrors($this->getEntity()->setContent('petit'), 1);
        $this->assertEquals("Le content doit faire au minimum 10 caractères", $errors[0]->getMessage());
    }   
}