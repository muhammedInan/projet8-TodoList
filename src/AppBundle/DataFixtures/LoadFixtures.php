<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadFixtures implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return object
     */
    public function getEntityManager()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * @return object
     */
    public function getSecurityPasswordEncoder()
    {
        return $this->container->get('security.password_encoder');
    }
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    /**
     * @param $username
     * @param $email
     * @param $role
     * @param $password
     * @return User
     */
    protected function createUserForDemo($username, $email, $roles, $password)
    {
        $user = new User;
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setRoles($roles);
       
        $passwordEncoder = $this->getSecurityPasswordEncoder();
        $passwordEncode = $passwordEncoder->encodePassword($user, $password);
        $user->setPassword($passwordEncode);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        return $user;
    }
    /**
     * @param $title
     * @param $content
     * @param $user
     * @return Task
     */
    protected function createTaskForDemo($title, $content, $user)
    {
        $task = new Task;
        $task->setTitle($title);
        $task->setContent($content);
        $task->setUser($user);
        $this->getEntityManager()->persist($task);
        $this->getEntityManager()->flush();
        return $task;
    }
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        //Users
        $user = $this->createUserForDemo('user', 'user@example.com', 'ROLE_USER', 'password');
        $admin = $this->createUserForDemo('admin', 'admin@example.com', 'ROLE_ADMIN', 'password');
        $user1 = $this->createUserForDemo('Lisy', 'lisy@example.com', 'ROLE_USER', 'password');
        $user2 = $this->createUserForDemo('Anna', 'anna@example.com', 'ROLE_ADMIN', 'password');
        $user3 = $this->createUserForDemo('Tally', 'tally@example.com', 'ROLE_USER', 'password');
        $manager->persist($user);
        $manager->persist($admin);
        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        //Tasks
        $task1 = $this->createTaskForDemo('1ère tâche', 'Contenu de la 1ère tâche.', $user1);
        $task2 = $this->createTaskForDemo('2ème tâche', 'Contenu de la 2ème tâche.', $user2);
        $task3 = $this->createTaskForDemo('3ème tâche', 'Contenu de la 3ème tâche.', null);
        $task4 = $this->createTaskForDemo('4ème tâche', 'Contenu de la 4ème tâche.', $user1);
        $task5 = $this->createTaskForDemo('5ème tâche', 'Contenu de la 5ème tâche.', $user3);
        $manager->persist($task1);
        $manager->persist($task2);
        $manager->persist($task3);
        $manager->persist($task4);
        $manager->persist($task5);
        // On déclenche l'enregistrement
        $manager->flush();
    }
}
