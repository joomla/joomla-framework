<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Router\Tests;

use Joomla\Router\Router;
use Joomla\Test\TestHelper;

require_once __DIR__ . '/Stubs/Bar.php';
require_once __DIR__ . '/Stubs/Baz.php';
require_once __DIR__ . '/Stubs/Foo.php';
require_once __DIR__ . '/Stubs/GooGet.php';

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
			array('', false, 'home', array(), 1),
			array('articles/4', true, 'home', array(), 1),
			array('', false, 'index', array(), 2),
			array('login', false, 'login', array('_rawRoute' => 'login'), 2),
			array('articles', false, 'articles', array('_rawRoute' => 'articles'), 2),
			array('articles/4', false, 'article', array('article_id' => 4, '_rawRoute' => 'articles/4'), 2),
			array('articles/4/crap', true, '', array(), 2),
			array('test', true, '', array(), 2),
			array('test/foo', true, '', array(), 2),
			array('test/foo/path', true, '', array(), 2),
			array('test/foo/path/bar', false, 'test', array('seg1' => 'foo', 'seg2' => 'bar', '_rawRoute' => 'test/foo/path/bar'), 2),
			array('content/article-1/*', false, 'content', array('_rawRoute' => 'content/article-1/*'), 2),
			array('content/cat-1/article-1', false,
				'article', array('category' => 'cat-1', 'article' => 'article-1', '_rawRoute' => 'content/cat-1/article-1'), 2),
			array('content/cat-1/cat-2/article-1', false,
				'article', array('category' => 'cat-1/cat-2', 'article' => 'article-1', '_rawRoute' => 'content/cat-1/cat-2/article-1'), 2),
			array('content/cat-1/cat-2/cat-3/article-1', false,
				'article', array('category' => 'cat-1/cat-2/cat-3', 'article' => 'article-1', '_rawRoute' => 'content/cat-1/cat-2/cat-3/article-1'), 2)
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
		$this->instance->setDefaultController('home');
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
		$this->instance->setDefaultController('index');
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
		$this->assertAttributeInstanceOf('Joomla\\Input\\Input', 'input', $this->instance);
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
	 * Tests the Joomla\Router\Router::getController method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Router\Router::getController
	 * @since   1.0
	 */
	public function testGetController()
	{
		$this->instance->setControllerPrefix('\Joomla\Router\Tests\Stubs\\')
			->addMap('articles/:article_id', 'GooGet');

		$controller = $this->instance->getController('articles/3');
		$this->assertInstanceOf('\Joomla\Router\Tests\Stubs\GooGet', $controller);

		$input = $controller->getInput();
		$this->assertEquals('3', $input->get('article_id'));
	}

	/**
	 * Tests the Joomla\Router\Router::parseRoute method.
	 *
	 * @param   string   $r  The route to parse.
	 * @param   boolean  $e  True if an exception is expected.
	 * @param   string   $c  The expected controller name.
	 * @param   array    $i  The expected input object data.
	 * @param   integer  $m  The map set to use for setting up the router.
	 *
	 * @return  void
	 *
	 * @covers        Joomla\Router\Router::parseRoute
	 * @dataProvider  seedTestParseRoute
	 * @since         1.0
	 */
	public function testParseRoute($r, $e, $c, $i, $m)
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
		$this->assertEquals($c, $actual, 'Incorrect controller name found.');
	}

	/**
	 * Tests the Joomla\Router\Router::setControllerPrefix method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Router\Router::setControllerPrefix
	 * @since   1.0
	 */
	public function testSetControllerPrefix()
	{
		$this->instance->setControllerPrefix('MyApplication');
		$this->assertAttributeEquals('MyApplication', 'controllerPrefix', $this->instance);
	}

	/**
	 * Tests the Joomla\Router\Router::setDefaultController method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Router\Router::setDefaultController
	 * @since   1.0
	 */
	public function testSetDefaultController()
	{
		$this->instance->setDefaultController('foobar');
		$this->assertAttributeEquals('foobar', 'default', $this->instance);
	}

	/**
	 * Tests the Joomla\Router\Router::fetchController method if the controller class is missing.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Router\Router::fetchController
	 * @since   1.0
	 */
	public function testFetchControllerWithMissingClass()
	{
		$this->setExpectedException('RuntimeException');
		$controller = TestHelper::invoke($this->instance, 'fetchController', 'goober');
	}

	/**
	 * Tests the Joomla\Router\Router::fetchController method if the class not a controller.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Router\Router::fetchController
	 * @since   1.0
	 */
	public function testFetchControllerWithNonController()
	{
		$this->setExpectedException('RuntimeException');
		$controller = TestHelper::invoke($this->instance, 'fetchController', 'MyTestControllerBaz');
	}

	/**
	 * Tests the Joomla\Router\Router::fetchController method with a prefix set.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Router\Router::fetchController
	 * @since   1.0
	 */
	public function testFetchControllerWithPrefixSet()
	{
		$this->instance->setControllerPrefix('MyTestController');
		$controller = TestHelper::invoke($this->instance, 'fetchController', 'Foo');
	}

	/**
	 * Tests the Joomla\Router\Router::fetchController method without a prefix set even though it is necessary.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Router\Router::fetchController
	 * @since   1.0
	 */
	public function testFetchControllerWithoutPrefixSetThoughNecessary()
	{
		$this->setExpectedException('RuntimeException');
		$controller = TestHelper::invoke($this->instance, 'fetchController', 'foo');
	}

	/**
	 * Tests the Joomla\Router\Router::fetchController method without a prefix set.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Router\Router::fetchController
	 * @since   1.0
	 */
	public function testFetchControllerWithoutPrefixSet()
	{
		$controller = TestHelper::invoke($this->instance, 'fetchController', 'TControllerBar');
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
