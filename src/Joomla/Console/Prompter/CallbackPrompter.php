<?php
/**
 * Part of jframework project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Prompter;

/**
 * Class ValidatePrompter
 *
 * @since 1.0
 */
class CallbackPrompter extends AbstractPrompter
{
	/**
	 * Property handler.
	 *
	 * @var  callable
	 */
	protected $handler = null;

	/**
	 * ask
	 *
	 * @param string $msg
	 * @param null   $default
	 *
	 * @throws \LogicException
	 * @return  null|string
	 */
	public function ask($msg = '', $default = null)
	{
		$value = trim($this->in($msg));

		$handler = $this->getHandler();

		if (!is_callable($handler))
		{
			throw new \LogicException('Please set a callable handler first.');
		}

		if ((boolean) call_user_func($this->getHandler(), $value))
		{
			return $value;
		}

		return $default;
	}

	/**
	 * setHandler
	 *
	 * @param   callable $handler
	 *
	 * @return  ValidatePrompter  Return self to support chaining.
	 */
	public function setHandler($handler)
	{
		$this->handler = $handler;

		return $this;
	}

	/**
	 * getHandler
	 *
	 * @return  callable
	 */
	public function getHandler()
	{
		return $this->handler;
	}
}
 