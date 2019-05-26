<?php

namespace Tests\AppBundle\Controller;


class DefaultControllerTest extends WebTestCase
{
    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->loadFixturesForTests();
    }

    public function testIndex()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Username',
            'PHP_AUTH_PW' => 'pass_1234',
        ));
        $crawler = $client->request('GET', '/');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertContains('Bienvenue sur Todo List', $crawler->filter('h1')->text());
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
}