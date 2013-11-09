<?php

namespace Joomla\Console\Tests\Stubs;

use Joomla\Console\Command\Command;
use Joomla\Console\Tests\Stubs\Foo\AaaCommand;

class FooCommand extends Command
{
	protected $name = 'foo';

	protected function configure()
	{
		$this->setDescription('Foo command desc')
			->setUsage('foo <command> [option]')
			->setHelp('Foo Command Help')
			->addArgument(new AaaCommand);
	}

	public function doExecute()
	{
		return 123;
	}
}