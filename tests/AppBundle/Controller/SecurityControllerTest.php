<?php

namespace Tests\AppBundle\Controller;

class SecurityControllerTest extends WebTestCase
{
     /**
     * @var Client
     */
    private $client;


	/**
     * {@inheritDoc}
     */
	public function setUp()
	{
		parent::setUp();
        $this->loadFixturesForTests();
        $this->client = static::createClient();
        $this->client->followRedirects();
	}
	public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
       
        $form['_username'] = 'Username';
        $form['_password'] = 'pass_1234';
     
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/'));
        $crawler = $client->followRedirect();
        $this->assertContains('Bienvenue sur Todo List', $client->getResponse()->getContent());    
    }

    public function testLoginGoodUser()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = "nicotest";
        $form['_password'] = "123456";
        $crawler = $this->client->submit($form);
        $this->assertSame(0, $crawler->filter('div.container:contains("Se déconnecter")')->count());
    }
    public function testLoginBadUser()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = "nicotest123"; // user doesn't exist
        $form['_password'] = "123456";
        $crawler = $this->client->submit($form);
        $this->assertSame(1, $crawler->filter('div.alert.alert-danger:contains("Invalid credentials.")')->count());
    }
   

/*     public function testLogout()
    {
     
        $link = $crawler->filter('a[href="/logout"]')->link();
        $crawler = $this->client->click($link);
        static::assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
    } */
    

    public function testLogout()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Username',
            'PHP_AUTH_PW'   => 'pass_1234',
       ));
       $crawler = $client->request('GET', '/logout');
      // $link = $crawler->filter('a[href="/logout"]')->link();
       $crawler = $client->followRedirect();
       static::assertEquals(302, $client->getResponse()->getStatusCode());
    
      //$link = $crawler->selectLink('Deconnexion')->link();
        //static::assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
   }

	/**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
}
