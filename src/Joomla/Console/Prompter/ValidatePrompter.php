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
	 * getHandler
	 *
	 * @return  callable
	 */
	public function getHandler()
	{
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
}
 