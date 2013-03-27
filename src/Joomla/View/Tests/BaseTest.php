<?php
/**
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\View\Tests;

use Joomla\Model;

require_once __DIR__ . '/stubs/tbase.php';

/**
 * Tests for the Joomla\View\Base class.
 *
 * @since  1.0
 */
class BaseTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    \Joomla\View\Base
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Tests the __construct method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\View\Base::__construct
	 * @since   1.0
	 */
	public function test__construct()
	{
		$this->assertAttributeInstanceOf('Joomla\\Model\\ModelInterface', 'model', $this->instance);
	}

	/**
	 * Tests the escape method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\View\Base::escape
	 * @since   1.0
	 */
	public function testEscape()
	{
		$this->assertEquals('foo', $this->instance->escape('foo'));
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

		$this->instance = new BaseView($model);
	}
}
