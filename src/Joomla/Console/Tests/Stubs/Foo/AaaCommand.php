<?php

namespace Joomla\Console\Tests\Stubs\Foo;

use Joomla\Console\Command\Command;

class AaaCommand extends Command
{
	protected $name = 'aaa';

	public function configure()
	{
		$this->addArgument(new Aaa\BbbCommand)
			->addOption(
				['a', 'aaa', 'a3'],
				true,
				'AAA options',
				true
			);
	}

	public function doExecute()
	{
		echo 'Aaa';
	}
}