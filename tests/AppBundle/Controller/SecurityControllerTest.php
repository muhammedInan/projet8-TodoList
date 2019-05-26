<?php

namespace Tests\AppBundle\Controller;



class SecurityControllerTest extends WebTestCase
{
    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->loadFixturesForTests();
    }
    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $crawler = $client->submit($form, array(
            '_username'	=> 'Username',
            '_password' => 'pass_1234',
        ));
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/'));
        $crawler = $client->followRedirect();
        $this->assertSame(0, $crawler->filter('div.alert.alert-danger')->count());
    }
    public function testLogout()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Username',
            'PHP_AUTH_PW'   => 'pass_1234',
        ));
        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('Se déconnecter')->link();
        $crawler = $client->click($link);
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/'));
    }
    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
}