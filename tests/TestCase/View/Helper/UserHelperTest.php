<?php
/**
 * Copyright 2010 - 2015, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2015, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace Users\Test\TestCase\View\Helper;

use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use Users\View\Helper\UserHelper;

/**
 * Users\View\Helper\UserHelper Test Case
 */
class UserHelperTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        Router::connect(':plugin/:controller/:action');
        $view = new View();
        $this->User = new UserHelper($view);
        $this->request = new \Cake\Network\Request();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->User);

        parent::tearDown();
    }

    /**
     * Test facebookLogin
     *
     * @return void
     */
    public function testFacebookLogin()
    {
        $result = $this->User->facebookLogin();
        $expected = '<a href="/auth/facebook" class="btn btn-social btn-facebook"><i class="fa fa-facebook"></i>Sign in with Facebook</a>';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test twitterLogin
     *
     * @return void
     */
    public function testTwitterLoginEnabled()
    {
        $result = $this->User->twitterLogin();
        $expected = '<a href="/auth/twitter" class="btn btn-social btn-twitter"><i class="fa fa-twitter"></i>Sign in with Twitter</a>';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test twitterLogin
     *
     * @return void
     */
    public function testLogout()
    {
        $result = $this->User->logout();
        $expected = '<a href="/Users/Users/logout">Logout</a>';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test twitterLogin
     *
     * @return void
     */
    public function testLogoutDifferentMessage()
    {
        $result = $this->User->logout('Sign Out');
        $expected = '<a href="/Users/Users/logout">Sign Out</a>';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test twitterLogin
     *
     * @return void
     */
    public function testLogoutWithOptions()
    {
        $result = $this->User->logout('Sign Out', ['class' => 'logout']);
        $expected = '<a href="/Users/Users/logout" class="logout">Sign Out</a>';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test link
     *
     * @return void
     */
    public function testLinkFalse()
    {
        $link = $this->User->link('title', ['controller' => 'noaccess']);
        $this->assertSame(false, $link);
    }

    /**
     * Test link
     *
     * @return void
     */
    public function testLinkAuthorized()
    {
        $view = new View();
        $eventManagerMock = $this->getMockBuilder('Cake\Event\EventManager')
                ->setMethods(['dispatch'])
                ->getMock();
        $view->eventManager($eventManagerMock);
        $this->User = new UserHelper($view);
        $result = new Event('dispatch-result');
        $result->result = true;
        $eventManagerMock->expects($this->once())
                ->method('dispatch')
                ->will($this->returnValue($result));

        $link = $this->User->link('title', '/', ['before' => 'before_', 'after' => '_after', 'class' => 'link-class']);
        $this->assertSame('before_<a href="/" class="link-class">title</a>_after', $link);
    }

    /**
     * Test link
     *
     * @return void
     */
    public function testWelcome()
    {
        $session = $this->getMock('Cake\Network\Session', ['read']);
        $session->expects($this->at(0))
            ->method('read')
            ->with('Auth.User.id')
            ->will($this->returnValue(2));

        $session->expects($this->at(1))
            ->method('read')
            ->with('Auth.User.first_name')
            ->will($this->returnValue('david'));

        $this->User->request = $this->getMock('Cake\Network\Request', ['session']);
        $this->User->request->expects($this->any())
            ->method('session')
            ->will($this->returnValue($session));

        $expected = '<span class="welcome">Welcome, <a href="/p/2">david</a></span>';
        $result = $this->User->welcome();
        $this->assertEquals($expected, $result);
    }

    /**
     * Test link
     *
     * @return void
     */
    public function testWelcomeNotLoggedInUser()
    {
        $session = $this->getMock('Cake\Network\Session', ['read']);
        $session->expects($this->at(0))
            ->method('read')
            ->with('Auth.User.id')
            ->will($this->returnValue(null));

        $this->User->request = $this->getMock('Cake\Network\Request', ['session']);
        $this->User->request->expects($this->any())
            ->method('session')
            ->will($this->returnValue($session));

        $result = $this->User->welcome();
        $this->assertEmpty($result);
    }
}