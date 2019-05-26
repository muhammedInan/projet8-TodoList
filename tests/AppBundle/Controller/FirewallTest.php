<?php

namespace Tests\AppBundle\Controller;

class FirewallTest extends WebTestCase
{
    private $client;
    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->loadFixturesForTests();
    }
    // ROUTES ACCESSIBLES TO ANONYMOUS

    public function testLoginRouteIsAccessibleToAnonymous()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
    // ROUTES ACCESSIBLE TO ROLE USER
    /**
     * @dataProvider urlRequiringAuthenticationProvider
     */
    public function testRoutesRequiringAuthentication($url)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', sprintf($url, $this->task->getId()));
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));
    }
    public function urlRequiringAuthenticationProvider()
    {
        return [
            ['/'],
            ['/tasks'],
            ['/tasks/create'],
            ['/tasks/%d/edit'],
            ['/tasks/%d/toggle'],
            ['/tasks/%d/delete'],
            ['/users'],
            ['/users/create'],
            ['/users/%d/edit']
        ];
    }
    // ROUTES ACCESSIBLE TO ROLE ADMIN

    /**
     * @dataProvider urlWithRoleAdminProvider
     */
    public function testRoutesRequiringRoleAdmin($url)
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Username',
            'PHP_AUTH_PW'   => 'pass_1234',
        ));
        $crawler = $client->request('GET', sprintf($url, $this->task->getId()));
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
    public function urlWithRoleAdminProvider()
    {
        return [
            ['/users'],
            ['/users/create'],
            ['/users/%d/edit']
        ];
    }
    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
}