<?php
/**
 * Part of jframework project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Prompter;

use Joomla\Input;
use Joomla\Application\Cli\Output\Stdout;

/**
 * Class ValidatePrompter
 *
 * @since 1.0
 */
class ValidatePrompter extends CallbackPrompter
{
	/**
	 * Property options.
	 *
	 * @var array
	 */
	protected $options = array();

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
	 * Constructor.
	 *
	 * @param array     $options
	 * @param Input\Cli $input
	 * @param Stdout    $output
	 */
	function __construct($options = array(), Input\Cli $input = null, Stdout $output = null)
	{
		$this->options = $options;

		parent::__construct($input, $output);
	}

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
		for ($i = 1; $i <= $this->attempt; $i++)
		{
			// Get parent ask process.
			if ($value = parent::ask($msg, null))
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
	 * getHandler
	 *
	 * @return  callable
	 */
	public function getHandler()
	{
		if (is_callable($this->handler))
		{
			return $this->handler;
		}

		$options = $this->options;

		return function($value) use ($options)
		{
			if (in_array($value, $options))
			{
				return true;
			}

			return false;
		};
	}

	/**
	 * addOption
	 *
	 * @param string $description
	 * @param string $option
	 *
	 * @return  $this
	 */
	public function addOption($description, $option = null)
	{
		if ($option)
		{
			$this->options[$option] = $description;
		}
		else
		{
			$this->options[] = $description;
		}

		return $this;
	}

	/**
	 * removeOption
	 *
	 * @param $key
	 *
	 * @return  $this
	 */
	public function removeOption($key)
	{
		if (!empty($this->options[$key]))
		{
			unset($this->options[$key]);
		}

		return $this;
	}

	/**
	 * setOptions
	 *
	 * @param $options
	 *
	 * @return  $this
	 */
	public function setOptions($options)
	{
		$this->options = $options;

		return $this;
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
 