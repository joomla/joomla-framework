<?php
/**
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\View\Tests;

use Joomla\Model;
use Joomla\Test\TestHelper;

require_once __DIR__ . '/stubs/thtml.php';

/**
 * Tests for the Joomla\View\AbstractHtmlView class.
 *
 * @since  1.0
 */
class AbstractHtmlViewTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    \Joomla\View\AbstractHtmlView
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Tests the __construct method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\View\AbstractHtmlView::__construct
	 * @since   1.0
	 */
	public function test__construct()
	{
		$this->assertAttributeEquals(new \SplPriorityQueue, 'paths', $this->instance, 'Check default paths.');

		$model = Model\Tests\Mock\Model::create($this);
		$paths = new \SplPriorityQueue;
		$paths->insert('foo', 1);

		$this->instance = new HtmlView($model, $paths);
		$this->assertAttributeSame($paths, 'paths', $this->instance, 'Check default paths.');
	}

	/**
	 * Tests the __toString method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\View\AbstractHtmlView::__toString
	 * @since   1.0
	 */
	public function test__toString()
	{
		// Set up a priority queue.
		$paths = $this->instance->getPaths();
		$paths->insert(__DIR__ . '/layouts1', 1);

		$this->instance->setLayout('olivia');
		$this->assertEquals($this->instance->setLayout('olivia'), (string) $this->instance);
	}

	/**
	 * Tests the escape method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\View\AbstractHtmlView::escape
	 * @since   1.0
	 */
	public function testEscape()
	{
		$this->assertEquals('&quot;', $this->instance->escape('"'));
	}

	/**
	 * Tests the getLayout method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\View\AbstractHtmlView::getLayout
	 * @since   1.0
	 */
	public function testGetLayout()
	{
		TestHelper::setValue($this->instance, 'layout', 'foo');

		$this->assertEquals('foo', $this->instance->getLayout());
	}

	/**
	 * Tests the getPath method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\View\AbstractHtmlView::getPath
	 * @since   1.0
	 */
	public function testGetPath()
	{
		// Set up a priority queue.
		$paths = $this->instance->getPaths();
		$paths->insert(__DIR__ . '/layouts1', 1);
		$paths->insert(__DIR__ . '/layouts2', 2);

		// Use of realpath to ensure test works for on all platforms
		$this->assertEquals(realpath(__DIR__ . '/layouts2/olivia.php'), $this->instance->getPath('olivia'));
		$this->assertEquals(realpath(__DIR__ . '/layouts1/peter.php'), $this->instance->getPath('peter'));
		$this->assertEquals(realpath(__DIR__ . '/layouts2/fauxlivia.php'), $this->instance->getPath('fauxlivia'));
		$this->assertEquals(realpath(__DIR__ . '/layouts1/fringe/division.php'), $this->instance->getPath('fringe/division'));
		$this->assertEquals(realpath(__DIR__ . '/layouts1/astrid.phtml'), $this->instance->getPath('astrid', 'phtml'));
		$this->assertFalse($this->instance->getPath('walter'));

		// Check dirty path.
		$this->assertEquals(realpath(__DIR__ . '/layouts1/fringe/division.php'), $this->instance->getPath('fringe//\\division'));
	}

	/**
	 * Tests the getPaths method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\View\AbstractHtmlView::getPaths
	 * @since   1.0
	 */
	public function testGetPaths()
	{
		// Inject a known value into the property.
		TestHelper::setValue($this->instance, 'paths', 'paths');

		// Check dirty path.
		$this->assertEquals('paths', $this->instance->getPaths());
	}

	/**
	 * Tests the render method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\View\AbstractHtmlView::render
	 * @since   1.0
	 */
	public function testRender()
	{
		// Set up a priority queue.
		$paths = $this->instance->getPaths();
		$paths->insert(__DIR__ . '/layouts1', 1);
		$paths->insert(__DIR__ . '/layouts2', 2);

		$this->instance->setLayout('olivia');
		$this->assertEquals('Peter\'s Olivia', trim($this->instance->render()));
	}

	/**
	 * Tests the render method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\View\AbstractHtmlView::render
	 * @since   1.0
	 *
	 * @expectedException  RuntimeException
	 */
	public function testRender_exception()
	{
		$this->instance->render();
	}

	/**
	 * Tests the setLayout method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\View\AbstractHtmlView::setLayout
	 * @since   1.0
	 */
	public function testSetLayout()
	{
		$result = $this->instance->setLayout('fringe/division');
		$this->assertAttributeSame('fringe/division', 'layout', $this->instance);
		$this->assertSame($this->instance, $result);
	}

	/**
	 * Tests the setPaths method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\View\AbstractHtmlView::setPaths
	 * @since   1.0
	 */
	public function testSetPaths()
	{
		$paths = new \SplPriorityQueue;
		$paths->insert('bar', 99);

		$result = $this->instance->setPaths($paths);
		$this->assertAttributeSame($paths, 'paths', $this->instance);
		$this->assertSame($this->instance, $result);
	}

	/**
	 * Tests the loadPaths method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\View\AbstractHtmlView::loadPaths
	 * @since   1.0
	 */
	public function testLoadPaths()
	{
		$this->assertEquals(new \SplPriorityQueue, TestHelper::invoke($this->instance, 'loadPaths'));
	}

	/**
	 * Setup the tests.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		$model = Model\Tests\Mock\Model::create($this);

		$this->instance = new HtmlView($model);
	}
}
