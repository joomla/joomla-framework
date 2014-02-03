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
class ValidatePrompter extends AbstractPrompter
{
	/**
	 * Property handler.
	 *
	 * @var  callable
	 */
	protected $handler = null;

	/**
	 * Property attempt.
	 *
	 * @var  int
	 */
	protected $attempt = 3;

	/**
	 * Property failToClose.
	 *
	 * @var  bool
	 */
	protected $failToClose = false;

	/**
	 * Property noValidMessage.
	 *
	 * @var  string
	 */
	protected $noValidMessage = '  Not a valid selection';

	/**
	 * Property closeMessage.
	 *
	 * @var  string
	 */
	protected $closeMessage = 'No selected and close.';

	/**
	 * ask
	 *
	 * @param string $msg
	 * @param null   $default
	 *
	 * @return  null|string
	 */
	public function ask($msg = '', $default = null)
	{
		for ($i = 1; $i <= $this->attempt; $i++)
		{
			$value = trim($this->in($msg));

			if (call_user_func($this->getHandler(), $value))
			{
				return $value;
			}

			$this->output->out($this->noValidMessage);
		}

		if ($this->failToClose)
		{
			$this->output->out()->out($this->closeMessage);

			die;
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

	/**
	 * setAttempt
	 *
	 * @param   int $attempt
	 *
	 * @return  ValidatePrompter  Return self to support chaining.
	 */
	public function setAttempt($attempt)
	{
		$this->attempt = $attempt;

		return $this;
	}

	/**
	 * setNoValidMessage
	 *
	 * @param   string $noValidMessage
	 *
	 * @return  ValidatePrompter  Return self to support chaining.
	 */
	public function setNoValidMessage($noValidMessage)
	{
		$this->noValidMessage = $noValidMessage;

		return $this;
	}

	/**
	 * setFailToClose
	 *
	 * @param   boolean $failToClose
	 * @param   string  $message
	 *
	 * @return  ValidatePrompter  Return self to support chaining.
	 */
	public function failToClose($failToClose = null, $message = '')
	{
		if (is_null($failToClose))
		{
			return $this->failToClose;
		}

		$this->failToClose  = $failToClose;
		$this->closeMessage = $message ? $message : $this->closeMessage;

		return $this;
}
}
 