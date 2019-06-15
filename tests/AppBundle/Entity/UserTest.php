<?php

namespace Tests\AppBundle\Entity;

use Tests\AppBundle\Controller\WebTestCase;
use AppBundle\Entity\User;

class UserTest extends WebTestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    public function testUserBlankData()
    {
        $user = new User();
        $user->setUsername('');
        $user->setEmail('');
        $validator = static::$kernel->getContainer()->get('validator');
        $violationList = $validator->validate($user);
        $this->assertEquals(3, $violationList->count());
        $this->assertSame('Vous devez saisir un nom d\'utilisateur.', $violationList[0]->getMessage());
        $this->assertSame('Vous devez saisir une adresse email.', $violationList[1]->getMessage());
    }
    public function testUserBadEmail()
    {
        $user = new User();
        $user->setUsername('Utilisateur');
        $user->setEmail('Je ne suis pas un email');
        $validator = static::$kernel->getContainer()->get('validator');
        $violationList = $validator->validate($user);
        $this->assertSame('Le format de l\'adresse n\'est pas correcte.', $violationList[0]->getMessage());
    }
    protected function tearDown()
    {
        parent::tearDown();
    }
}
