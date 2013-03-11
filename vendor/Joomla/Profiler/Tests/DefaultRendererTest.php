<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Profiler\Tests;

use Joomla\Profiler\Renderer\DefaultRenderer;
use Joomla\Profiler\ProfilePoint;
use Joomla\Profiler\Profiler;

/**
 * Tests for the DefaultRenderer class.
 *
 * @since  1.0
 */
class DefaultRendererTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    \Joomla\Profiler\Renderer\DefaultRenderer
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Tests the render method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Renderer\DefaultRenderer::render
	 * @since   1.0
	 */
	public function testRender()
	{
		// Create a few points.
		$first = new ProfilePoint('first');
		$second = new ProfilePoint('second', 1.5, 1048576);
		$third = new ProfilePoint('third', 2.5, 2097152);
		$fourth = new ProfilePoint('fourth', 3, 1572864);

		// Create a profiler and inject the points.
		$profiler = new Profiler('test', null, array($first, $second, $third, $fourth));

		$expectedString = '<code>test 0.000 seconds (+0.000); 0.00 MB (0.000) - first</code><br />';
		$expectedString .= '<code>test 1.500 seconds (+1.500); 1.00 MB (+1.000) - second</code><br />';
		$expectedString .= '<code>test 2.500 seconds (+1.000); 2.00 MB (+1.000) - third</code><br />';
		$expectedString .= '<code>test 3.000 seconds (+0.500); 1.50 MB (-0.500) - fourth</code><br />';

		$this->assertEquals($this->instance->render($profiler), $expectedString);
	}

	/**
	 * Tests the render method with an empty profiler.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Renderer\DefaultRenderer::render
	 * @since   1.0
	 */
	public function testRenderEmpty()
	{
		$profiler = new Profiler('test');

		$this->assertEmpty($this->instance->render($profiler));
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

		$this->instance = new DefaultRenderer;
	}
}
