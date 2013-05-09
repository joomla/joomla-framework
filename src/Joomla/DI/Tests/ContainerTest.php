<?php

namespace Joomla\DI\Tests;

use Joomla\DI\Container;

class ContainerTest
{
	/**
 	 * Holds the Container instance for testing.
	 *
	 * @var  Joomla\DI\Container
	 */
	protected $fixture;

	public function setUp()
	{
		$this->fixture = new Container;
	}

	public function tearDown()
	{
		$this->fixture = null;
	}

	public function testConstructor()
	{
		$this->markTestIncomplete();
	}

	public function testSet()
	{
		$this->markTestIncomplete();
	}

	public function testGet()
	{
		$this->markTestIncomplete();
	}

	public function testSetConfig()
	{
		$this->markTestIncomplete();
	}

	public function testGetConfig()
	{
		$this->markTestIncomplete();
	}

	public function testSetParam()
	{
		$this->markTestIncomplete();
	}

	public function testGetParam()
	{
		$this->markTestIncomplete();
	}
}
