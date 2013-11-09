<?php

namespace Joomla\Console\Tests\Stubs\Foo\Aaa;

use Joomla\Console\Command\Command;

class BbbCommand extends Command
{
	public function configure()
	{
		$this->setName('bbb');
	}

	public function doExecute()
	{
		$this->out('Bbb Command', false);

		return 99;
	}
}