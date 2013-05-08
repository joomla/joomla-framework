<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Facebook\Tests;

use Joomla\Facebook\OAuth;
use Joomla\Registry\Registry;
use Joomla\Input\Input;
use Joomla\Test\WebInspector;

require_once __DIR__ . '/case/FacebookTestCase.php';

/**
 * Test class for Joomla\Facebook\OAuth.
 *
 * @since       1.0
 */
class OAuthTest extends FacebookTestCase
{
	/**
	 * @var    JApplicationWeb  The application object to send HTTP headers for redirects.
	 */
	protected $application;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0';
		$_SERVER['REQUEST_URI'] = '/index.php';
		$_SERVER['SCRIPT_NAME'] = '/index.php';

		$this->options = new Registry;
		$this->client = $this->getMock('\\Joomla\\Http\\Http', array('get', 'post', 'delete', 'put'));
		$this->input = new Input;

		$this->application = new WebInspector;
		$this->object = new OAuth($this->options, $this->client, $this->input, $this->application);
	}

	/**
	 * Tests the setScope method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSetScope()
	{
		$this->object->setScope('read_stream');

		$this->assertThat(
			$this->options->get('scope'),
			$this->equalTo('read_stream')
		);
	}

	/**
	 * Tests the getScope method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetScope()
	{
		$this->options->set('scope', 'read_stream');

		$this->assertThat(
			$this->object->getScope(),
			$this->equalTo('read_stream')
		);
	}
}
