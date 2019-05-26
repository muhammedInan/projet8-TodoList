<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use AppBundle\Entity\User;
use AppBundle\Entity\Task;

class WebTestCase extends BaseWebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    /**
     * @var \AppBundle\Entity\User
     */
    protected $user;
    /**
     * @var \AppBundle\Entity\Task
     */
    protected $task;
    /**
     * @var \AppBundle\Entity\User
     */
    protected $admin;
    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();
        self::bootKernel();
        $this->entityManager = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
    public function loadFixturesForTests()
    {
        $this->entityManager->createQuery('DELETE AppBundle:Task t')->execute();
        $this->entityManager->createQuery('DELETE AppBundle:User u')->execute();
        $user = new User();
        $user->setUsername('Username');
        $encoder = static::$kernel->getContainer()->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, 'pass_1234');
        $user->setPassword($encoded);
        $user->setEmail('email@example.com');
        $user->setRoles(['ROLE_USER']);
        $this->entityManager->persist($user);
        $admin = new User();
        $admin->setUsername('Admin');
        $encoder = static::$kernel->getContainer()->get('security.password_encoder');
        $encoded = $encoder->encodePassword($admin, 'pass_1234');
        $admin->setPassword($encoded);
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $this->entityManager->persist($admin);
        $task = new Task();
        $task->setTitle('Un titre de test');
        $task->setContent('Une description');
        $task->setCreatedAt(new \Datetime('2018-05-25 12:00:00'));
        $task->toggle(false);
        $task->setUser($user);
        $this->entityManager->persist($task);
        $this->entityManager->flush();
        $this->user = $user;
        $this->admin = $admin;
        $this->task = $task;
    }
    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
        $this->user = null;
        $this->task = null;
    }
}