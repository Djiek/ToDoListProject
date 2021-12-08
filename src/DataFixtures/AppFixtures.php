<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
     private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $user = [];
        for ($k = 1; $k < 8; $k++) {
                    $user = new User();
                    $user->setUsername($faker->firstName())
                        ->setRoles(["ROLE_USER"])
                        ->setPassword($this->passwordEncoder->encodePassword($user, $faker->password()))
                        ->setEmail($faker->email());
                    $users[] = $user;
                    $manager->persist($user);
                }

        $anonymeUser =  new User();
        $anonymeUser->setUsername("anonyme")
                ->setRoles(["ROLE_ADMIN"])
                ->setPassword($this->passwordEncoder->encodePassword($anonymeUser, $faker->password()))
                ->setEmail("anonyme@gmail.com");
            $manager->persist($anonymeUser);

        $AdminUser =  new User();
        $AdminUser->setUsername("Administrateur")
                ->setRoles(["ROLE_ADMIN"])
                ->setPassword($this->passwordEncoder->encodePassword($AdminUser, "12345678"))
                ->setEmail("administrateur@gmail.com");
            $manager->persist($AdminUser); 

        $taskAdmin = [];
        for ($j = 1; $j <= 3; $j++) {
                $taskAdmin = new Task();
                $taskAdmin->setTitle($faker->word(5))
                    ->setUser($AdminUser)
                    ->setCreatedAt($faker->dateTime($max = 'now', $timezone = null))
                    ->setContent($faker->sentence(10))
                    ->setIsDone(0);
                $taskAdmins[] = $taskAdmin;
                $manager->persist($taskAdmin);
        }  

        $task = [];
        for ($j = 1; $j <= 10; $j++) {
                $task = new Task();
                $task->setTitle($faker->word(5))
                        ->setUser($users[array_rand($users)])
                        ->setCreatedAt($faker->dateTime($max = 'now', $timezone = null))
                        ->setContent($faker->sentence(10))
                        ->setIsDone(0);
                $tasks[] = $task;
                $manager->persist($task);
        }  

        $taskAnonyme = [];
        for ($j = 1; $j <= 5; $j++) {
            $taskAnonyme = new Task();
            $taskAnonyme->setTitle($faker->word(5))
                    ->setCreatedAt($faker->dateTime($max = 'now', $timezone = null))
                    ->setContent($faker->sentence(10))
                    ->setIsDone(1)
                    ->setUser($anonymeUser);
                    $taskAnonymes[] = $taskAnonyme;
                    $manager->persist($taskAnonyme);
        }
        $manager->flush();
    }
}
