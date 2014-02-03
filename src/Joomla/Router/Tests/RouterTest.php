<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Router\Tests;

use Joomla\Router\Router;
use Joomla\Test\TestHelper;

/**
 * Tests for the Joomla\Router\Router class.
 *
 * @since  1.0
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * An instance of the object to be tested.
	 *
	 * @var    Router
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Provides test data for the testParseRoute method.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public static function seedTestParseRoute()
	{
		// Route, Exception, ControllerName, InputData, MapSet
		return array(
			array('', true, array(), 1),
			array('articles/4', true, array(), 1),
			array('', true, array(), 2),
			array('login', false, array('controller' => 'login', 'vars' => array('_rawRoute' => 'login')), 2),
			array('articles', false, array('controller' => 'articles', 'vars' => array('_rawRoute' => 'articles')), 2),
			array('articles/4', false, array('controller' => 'article', 'vars' => array('article_id' => 4, '_rawRoute' => 'articles/4')), 2),
			array('articles/4/crap', true, array(), 2),
			array('test', true, array(), 2),
			array('test/foo', true, array(), 2),
			array('test/foo/path', true, array(), 2),
			array('test/foo/path/bar', false, array('controller' => 'test', 'vars' => array('seg1' => 'foo', 'seg2' => 'bar', '_rawRoute' => 'test/foo/path/bar')), 2),
			array('content/article-1/*', false, array('controller' => 'content', 'vars' => array('_rawRoute' => 'content/article-1/*')), 2),
			array('content/cat-1/article-1', false,
				array('controller' => 'article', 'vars' => array('category' => 'cat-1', 'article' => 'article-1', '_rawRoute' => 'content/cat-1/article-1')), 2),
			array('content/cat-1/cat-2/article-1', false,
				array('controller' => 'article', 'vars' => array('category' => 'cat-1/cat-2', 'article' => 'article-1', '_rawRoute' => 'content/cat-1/cat-2/article-1')), 2),
			array('content/cat-1/cat-2/cat-3/article-1', false,
				array('controller' => 'article', 'vars' => array('category' => 'cat-1/cat-2/cat-3', 'article' => 'article-1', '_rawRoute' => 'content/cat-1/cat-2/cat-3/article-1')), 2)
		);
	}

	/**
	 * Setup the router maps to option 1.
	 *
	 * This has no routes but has a default controller for the home page.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setMaps1()
	{
		$this->instance->addMaps(array());
	}

	/**
	 * Setup the router maps to option 2.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setMaps2()
	{
		$this->instance->addMaps(
			array(
				'login' => 'login',
				'logout' => 'logout',
				'articles' => 'articles',
				'articles/:article_id' => 'article',
				'test/:seg1/path/:seg2' => 'test',
				'content/:/\*' => 'content',
				'content/*category/:article' => 'article'
			)
		);
	}

	/**
	 * Tests the Joomla\Router\Router::__construct method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Router\Router::__construct
	 * @since   1.0
	 */
	public function test__construct()
	{
		$this->assertAttributeEmpty('maps', $this->instance);
	}

	/**
	 * Tests the Joomla\Router\Router::__construct method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Router\Router::__construct
	 * @since   1.0
	 */
	public function test__constructNotEmpty()
	{
		$maps = array(
			'login' => 'login',
			'logout' => 'logout',
			'requests' => 'requests',
			'requests/:request_id' => 'request'
		);

		$rules = array(
			array(
				'regex' => chr(1) . '^login$' . chr(1),
				'vars' => array(),
				'controller' => 'login'
			),
			array(
				'regex' => chr(1) . '^logout$' . chr(1),
				'vars' => array(),
				'controller' => 'logout'
			),
			array(
				'regex' => chr(1) . '^requests$' . chr(1),
				'vars' => array(),
				'controller' => 'requests'
			),
			array(
				'regex' => chr(1) . '^requests/([^/]*)$' . chr(1),
				'vars' => array('request_id'),
				'controller' => 'request'
			)
		);

		$router = new Router($maps);

		$this->assertAttributeEquals(
			$rules,
			'maps',
			$router,
			'When passing an array of routes when instantiating a Router, the maps property should be set accordingly.'
		);
	}

	/**
	 * Tests the Joomla\Router\Router::addMap method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Router\Router::addMap
	 * @since   1.0
	 */
	public function testAddMap()
	{
		$this->assertAttributeEmpty('maps', $this->instance);
		$this->instance->addMap('foo', 'MyApplicationFoo');
		$this->assertAttributeEquals(
			array(
				array(
					'regex' => chr(1) . '^foo$' . chr(1),
					'vars' => array(),
					'controller' => 'MyApplicationFoo'
				)
			),
			'maps',
			$this->instance
		);
	}

	/**
	 * Tests the Joomla\Router\Router::addMaps method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Router\Router::addMaps
	 * @since   1.0
	 */
	public function testAddMaps()
	{
		$maps = array(
			'login' => 'login',
			'logout' => 'logout',
			'requests' => 'requests',
			'requests/:request_id' => 'request'
		);

		$rules = array(
			array(
				'regex' => chr(1) . '^login$' . chr(1),
				'vars' => array(),
				'controller' => 'login'
			),
			array(
				'regex' => chr(1) . '^logout$' . chr(1),
				'vars' => array(),
				'controller' => 'logout'
			),
			array(
				'regex' => chr(1) . '^requests$' . chr(1),
				'vars' => array(),
				'controller' => 'requests'
			),
			array(
				'regex' => chr(1) . '^requests/([^/]*)$' . chr(1),
				'vars' => array('request_id'),
				'controller' => 'request'
			)
		);

		$this->assertAttributeEmpty('maps', $this->instance);
		$this->instance->addMaps($maps);
		$this->assertAttributeEquals($rules, 'maps', $this->instance);
	}

	/**
	 * Tests the Joomla\Router\Router::parseRoute method.
	 *
	 * @param   string   $r  The route to parse.
	 * @param   boolean  $e  True if an exception is expected.
	 * @param   array    $i  The expected return data.
	 * @param   integer  $m  The map set to use for setting up the router.
	 *
	 * @return  void
	 *
	 * @covers        Joomla\Router\Router::parseRoute
	 * @dataProvider  seedTestParseRoute
	 * @since         1.0
	 */
	public function testParseRoute($r, $e, $i, $m)
	{
		// Setup the router maps.
		$mapSetup = 'setMaps' . $m;
		$this->$mapSetup();

		// If we should expect an exception set that up.
		if ($e)
		{
			$this->setExpectedException('InvalidArgumentException');
		}

		// Execute the route parsing.
		$actual = TestHelper::invoke($this->instance, 'parseRoute', $r);

		// Test the assertions.
		$this->assertEquals($i, $actual, 'Incorrect value returned.');
	}

	/**
	 * Prepares the environment before running a test.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->instance = new Router;
	}
}
