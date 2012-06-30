<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Twitter
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_PLATFORM . '/joomla/twitter/twitter.php';
require_once JPATH_PLATFORM . '/joomla/twitter/http.php';
require_once JPATH_PLATFORM . '/joomla/twitter/users.php';

/**
 * Test class for JTwitterUsers.
 * 
 * @package     Joomla.UnitTest
 * @subpackage  Twitter
 *
 * @since       12.1
 */
class JTwitterUsersTest extends TestCase
{
	/**
	 * @var    JRegistry  Options for the Twitter object.
	 * @since  12.1
	 */
	protected $options;

	/**
	 * @var    JTwitterHttp  Mock client object.
	 * @since  12.1
	 */
	protected $client;

	/**
	 * @var    JTwitterUsers  Object under test.
	 * @since  12.1
	 */
	protected $object;

	/**
	 * @var    JTwitterOAuth  Authentication object for the Twitter object.
	 * @since  12.1
	 */
	protected $oauth;

	/**
	 * @var    string  Sample JSON string.
	 * @since  12.1
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.1
	 */
	protected $errorString = '{"error": "Generic Error"}';

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 * 
	 * @return void
	 */
	protected function setUp()
	{
		$key = "lIio7RcLe5IASG5jpnZrA";
		$secret = "dl3BrWij7LT04NUpy37BRJxGXpWgjNvMrneuQ11EveE";
		$my_url = "http://127.0.0.1/gsoc/joomla-platform/twitter_test.php";

		$this->options = new JRegistry;
		$this->client = $this->getMock('JTwitterHttp', array('get', 'post', 'delete', 'put'));

		$this->object = new JTwitterUsers($this->options, $this->client);
		$this->oauth = new JTwitterOAuth($key, $secret, $my_url, $this->client);
		$this->oauth->setToken($key, $secret);
	}

	protected function getMethod($name)
	{
		$class = new ReflectionClass('JTwitterUsers');
		$method = $class->getMethod($name);
		$method->setAccessible(true);
		return $method;
	}

	/**
	 * Tests the getUsersLookup method
	 * 
	 * @covers JTwitterUsers::getUsersLookup
	 * 
	 * @todo   Implement testGetUsersLookup().
	 * 
	 * @return  void
	 */
	public function testGetUsersLookup()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the getUserProfileImage method
	 * 
	 * @covers JTwitterUsers::getUserProfileImage
	 * 
	 * @todo   Implement testGetUserProfileImage().
	 * 
	 * @return  void
	 */
	public function testGetUserProfileImage()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the searchUsers method
	 * 
	 * @covers JTwitterUsers::searchUsers
	 * 
	 * @todo   Implement testSearchUsers().
	 * 
	 * @return  void
	 */
	public function testSearchUsers()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the getUser method
	 * 
	 * @covers JTwitterUsers::getUser
	 * 
	 * @todo   Implement testGetUser().
	 * 
	 * @return  void
	 */
	public function testGetUser()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the getContributees method
	 * 
	 * @covers JTwitterUsers::getContributees
	 * 
	 * @todo   Implement testGetContributees().
	 * 
	 * @return  void
	 */
	public function testGetContributees()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the getContributors method
	 * 
	 * @covers JTwitterUsers::getContributors
	 * 
	 * @todo   Implement testGetContributors().
	 * 
	 * @return  void
	 */
	public function testGetContributors()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the getSuggestions method
	 * 
	 * @covers JTwitterUsers::getSuggestions
	 * 
	 * @todo   Implement testGetSuggestions().
	 * 
	 * @return  void
	 */
	public function testGetSuggestions()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the getSuggestionsSlug method
	 * 
	 * @covers JTwitterUsers::getSuggestionsSlug
	 * 
	 * @todo   Implement testGetSuggestionsSlug().
	 * 
	 * @return  void
	 */
	public function testGetSuggestionsSlug()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the getSuggestionsSlugMembers method
	 * 
	 * @covers JTwitterUsers::getSuggestionsSlugMembers
	 * 
	 * @todo   Implement testGetSuggestionsSlugMembers().
	 * 
	 * @return  void
	 */
	public function testGetSuggestionsSlugMembers()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
