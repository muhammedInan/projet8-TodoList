<?php

namespace Tests\AppBundle\Controller;

class TaskControllerTest extends WebTestCase
{
    use LogTrait, CreateTrait;

    private $client;
    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Username',
            'PHP_AUTH_PW'   => 'pass_1234',
        ));
      
    }
    public function testCreateNewTask()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
       
        $form['_username'] = 'Username';
        $form['_password'] = 'pass_1234';
     
        $client->submit($form);
        // Request the route
        $crawler = $client->request('GET', '/tasks/create');
        // Test
        $this->assertEquals(
            1,
            $crawler->filter('form')->count()
        );
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // Select the form
        $form = $crawler->selectButton('Ajouter')->form();
        // set some values
        $form['task[title]'] = 'A test title';
        $form['task[content]'] = 'A great content!';
        // submit the form
        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        // Test
        $this->assertTrue($client->getResponse()->isRedirect());
    }
    public function testEditTask()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
       
        $form['_username'] = 'Username';
        $form['_password'] = 'pass_1234';
     
        $client->submit($form);
        // Request the route
        $crawler = $client->request('GET', '/tasks/create');
        // Test
        $this->assertEquals(
            1,
            $crawler->filter('form')->count()
        );
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // Select the form
        $form = $crawler->selectButton('Ajouter')->form();
        // set some values
        $form['task[title]'] = 'A test title';
        $form['task[content]'] = 'A great content!';
        // submit the form
        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        // Test
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    /**
     * Test de suppression d'une tâche par un administrateur
     */
    public function testTaskDelete()
    {
        $user = $this->logInAdmin();
        $task = $this->createTask($user);
        $this->client->request('GET', 'tasks/'. $task->getId() .'/delete');
        $response = $this->client->getResponse();
        $this->assertSame(302, $response->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert-success:contains("La tâche a bien été supprimée.")')->count());
    }
    /**
     * Test de suppression d'une tâche d'un utilisateur anonyme par un administrateur
     */
    public function testAnonymousTaskDeleteByAdmin()
    {
        $crawler = $this->client->request('GET', '/tasks/5/delete', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $crawler = $this->client->followRedirect();
        static::assertSame(0, $crawler->filter('html:contains("La tâche a bien été supprimée.")')->count());
    }
    /**
     * Test de suppression d'une tâche admin par u nutilisateur ROLE_USER
     */
    public function testTaskDeleteByBadAuthor()
    {
        $crawler = $this->client->request('GET', '/tasks/1/delete', array(), array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $this->client->followRedirect();
        static::assertSame(0, $crawler->filter('html:contains("devez")')->count());
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
}