<?php
namespace Tests\AppBundle\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
class UserControllerTest extends WebTestCase
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
            'PHP_AUTH_USER' => 'Admin',
            'PHP_AUTH_PW'   => 'pass_1234',
        ));
        $this->loadFixturesForTests();
    }
    public function testCreateNewUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'Username';
        $form['_password'] = 'pass_1234';
        $client->submit($form);
        // Request the route
        $crawler = $client->request('GET', '/users/create');
        // Test
        $this->assertEquals(
            1,
            $crawler->filter('form')->count()
        );
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        // Select the form
        $form = $crawler->selectButton('Ajouter')->form();
        // set some values
        $form['user[username]'] = 'userTest';
        $form['user[password][first]'] = 'Aa@123';
        $form['user[password][second]'] = 'Aa@123';
        $form['user[email]'] = 'userTest@test.com';
        $form['user[roles]'] = 'ROLE_USER';
        // submit the form
        $crawler = $client->submit($form);
        // Test
        $this->assertTrue($client->getResponse()->isRedirect());
    }
    /**
     * Test de modification d'un utilisateur par un administrateur ROLE_ADMIN
     */
    public function testEditUserByAdmin()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/users/1/edit');
        static::assertEquals(404, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'user';
        $form['user[password][first]'] = 'userpassword';
        $form['user[password][second]'] = 'userpassword';
        $form['user[email]'] = 'test@test.com';
        $form['user[roles]'] = 'ROLE_USER';
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success:contains("modifié")')->count());
    }
    /**
     * Test de modification d'un utilisateur par un utilisateur ROLE_USER
     */
    public function testEditUserByUser()
    {
        $this->logInUser();
        $crawler = $this->client->request('GET', '/users/1/edit');
        static::assertEquals(404, $this->client->getResponse()->getStatusCode());
    }
    public function testUserRouteNotAccessibleToRoleUser()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Username',
            'PHP_AUTH_PW'   => 'pass_1234',
        ));
        $crawler = $client->request('GET', '/users');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }
    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
}