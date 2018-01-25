<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;

/**
 * Class contains all functional tests for this web application.
 *
 */
class FunctionalTests extends TestCase {
    /* Change this to suite your server setup */
    protected $baseUrl = "http://localhost/imt2291-web-technology/lab0123/index.php";
    protected $session;

    /**
     * Initiates the testing session, this is done before each test.
     * Starts a new session.
     */
    protected function setup() {
        $driver = new \Behat\Mink\Driver\GoutteDriver();
        $this->session = new \Behat\Mink\Session($driver);
        $this->session->start();
    }

    /**
     * Check that we get the right initial page (not logged in)
     */
    public function testInitialPage() {
        $this->session->visit($this->baseUrl);
        $page = $this->session->getPage();

        $this->assertInstanceOf(
            NodeElement::Class,
            $page->find('css', 'p'),
            'Should have a p tag in this page'
        );
        $this->assertEquals('Ikke logget inn', $page->find('css', 'p')->getText(),
            'Wrong text in paragraph');
    }

    /**
     * Get default page, submit the login form, check that we get the
     * logged in page, then reload the page and check that we are still
     * logged in.
     * NOTE: depends on there existing a testuser with the username 'testuser'
     * and the password 'testuser' on the server
     */
    public function testCanLogIn() {
        $this->session->visit($this->baseUrl);
        $page = $this->session->getPage();

        // log in
        $form = $page->find('css', '#login');
        $form->fillField('username', 'testuser');
        $form->fillField('password', 'testpassword');

        $this->assertInstanceOf(NodeElement::Class, $form, 'Unable to locate login form');

        $form->submit();

        // check if we're logged in (should contain a h1)
        $this->assertInstanceOf(
            NodeElement::Class,
            $page->find('css', 'h1'),
            'page should have a h1 when logged in (is the test user in the db?)'
        );

        // reload page
        $this->session->visit($this->baseUrl);
        $page = $this->session->getPage();

        // Check that we are still logged in
        $this->assertInstanceOf(
            NodeElement::Class,
            $page->find('css', 'h1'),
            'Logged out after reload'
        );

    }

    /**
     * Test that we can log out, also that we stay logged out when
     * page is reloaded.
     */
    public function testCanLogOut() {
        // TBA
    }
}
