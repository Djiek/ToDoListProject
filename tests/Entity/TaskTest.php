<?php
//* @Assert\Length(min="10",minMessage="Le titre doit faire au minimum 10 caractères")
//* @Assert\Length(min="10",minMessage="Le content doit faire au minimum 10 caractères")
// + les not blank =>  @Assert\NotBlank(message="veuillez saisir un contenu.")  @Assert\NotBlank(message="veuillez saisir un titre.")
namespace Tests\Entity;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class TaskTest extends KernelTestCase
{
    public function getEntity() : Task 
    {
        return (new Task())
        ->setTitle('Un titre')
        ->setContent('Je suis un contenu de 20 caractères minimum')
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
        $errors =  $this->assertHasErrors($this->getEntity()->setContent('Un contenu court'), 1);
        $this->assertEquals("Le content doit faire au minimum 20 caractères", $errors[0]->getMessage());
    }   
}