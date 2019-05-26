<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserControllerTest extends WebTestCase
{
    private $client;
    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Admin',
            'PHP_AUTH_PW'   => 'pass_1234',
        ));

        $this->loadFixturesForTests();
    }
    public function testCreateNewUser()
    {
        $crawler = $this->client->request('GET', '/users/create');
        $form = $crawler->selectButton('Ajouter')->form();
        $crawler = $this->client->submit($form, array(
            'user[username]'         => 'OtherUsername',
            'user[password][first]'  => 'password',
            'user[password][second]' => 'password',
            'user[email]'            => 'other_email@example.com'
        ));
        $crawler = $this->client->followRedirect();
        $this->assertContains('L\'utilisateur a bien été ajouté.', $crawler->filter('div.alert-success')->text());
        $user = $this->entityManager->getRepository('AppBundle:User')->findByUsername('OtherUsername');
        $this->assertCount(1, $user);
    }
    public function testEditUser()
    {
        $crawler = $this->client->request('GET', '/users/'.$this->user->getId().'/edit');
        $form = $crawler->selectButton('Modifier')->form();
        $crawler = $this->client->submit($form, array(
            'user[username]' => 'NewUsername'
        ));
        $crawler = $this->client->followRedirect();
        $this->assertContains('Superbe ! L\'utilisateur a bien été modifié', $crawler->filter('div.alert-success')->text());
        $this->entityManager->refresh($this->user);
        $this->assertSame('NewUsername', $this->user->getUsername());
    }
    public function testUserRouteNotAccessibleToRoleUser()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Username',
            'PHP_AUTH_PW'   => 'pass_1234',
        ));
        $crawler = $client->request('GET', '/users');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }
    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
}