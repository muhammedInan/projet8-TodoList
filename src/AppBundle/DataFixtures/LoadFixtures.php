<?php

namespace AppBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PHPUnit\Framework\TestCase;

/**
 * @codeCoverageIgnore
 */
class LoadFixtures extends AbstractFixture implements ContainerAwareInterface
{
    private $container;
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.password_encoder');
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setPassword($encoder->encodePassword($admin, 'admin'));
        $admin->setEmail('admin@admin.com');
        $admin->setRoles(array('ROLE_ADMIN'));
        $manager->persist($admin);
        $user = new User();
        $user->setUsername('user');
        $user->setPassword($encoder->encodePassword($user, 'user'));
        $user->setEmail('user@user.com');
        $user->setRoles(array('ROLE_USER'));
        $manager->persist($user);
        // Create Task without User
        $taskNoUser = new Task();
        $taskNoUser->setTitle('Tâche sans Utilisateur');
        $taskNoUser->setContent('Cette tâche ne possède aucun utilisateur.');
        $manager->persist($taskNoUser);
        $task = new Task();
        $task->setTitle('Tâche avec Utilisateur');
        $task->setContent('Cette tâche possède un utilisateur.');
        $task->setUser($user);
        $manager->persist($task);
        $taskadmin = new Task();
        $taskadmin->setTitle('Tâche avec Administrateur');
        $taskadmin->setContent('Cette tâche possède un Administrateur.');
        $taskadmin->setUser($admin);
        $manager->persist($taskadmin);
        $manager->flush();
    }
}