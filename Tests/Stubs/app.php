<?php

// Load the Composer autoloader
error_reporting(32767);

include __DIR__ . '/../../../../../vendor/autoload.php';

$app = new Joomla\Console\Console(null, null, new Joomla\Console\Output\Stdout);

$app->register('yoo')
	->setDescription('Yoo command')
	->addArgument(new \Asika\AaaCommand)
	->setCode(
		function($command, $input, $output)
		{
			$command->out('<comment>Yoo Commend</comment>');
		}
	);

$app->execute();
