<?php

namespace Joomla\Console\Tests\Stubs;

use Joomla\Console\Command\Command;

class FooCommand extends Command
{
	protected $name = 'foo';

	protected function configure()
	{
		$this->setDescription('Foo command desc')
			->setUsage('foo <command> [option]')
			->setHelp('Foo Command Help');
	}

	public function doExecute()
	{
		return 123;
	}
}