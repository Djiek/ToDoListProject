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

class TaskTest extends KernelTestCase
{
    private const NOT_BLANK_MESSAGE_CONTENT = "Veuillez saisir un contenu.";
    private const NOT_BLANK_MESSAGE_TITLE = "Veuillez saisir un titre.";
   

     public function getEntity() : Task 
    {
        return (new Task());
    }

    public function getValidationErrors(Task $task, int $number = 0) : ConstraintViolationList
    {     
        $validator = Validation::createValidator();
        $errors = $validator->validate($task);
        $this->assertCount($number,$errors);
        return $errors;
    }

    public function testIsValidBlanktitle() 
    {
        $this->getValidationErrors( $this->getEntity()->setTitle('Un Titre Valide'), 0);  
    }

    public function testInvalidBlanktitle() 
    {
        $errors = $this->getValidationErrors( $this->getEntity()->setTitle('non'), 1);
        $this->assertEquals("Veuillez saisir un contenu.", $errors[0]->getTitle());
    }
}