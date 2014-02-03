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
class SelectPrompter extends ValidatePrompter
{
	/**
	 * Property options.
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * Property listTemplate.
	 *
	 * @var  string
	 */
	protected $listTemplate = " %-{WIDTH}s[%s] - %s";

	/**
	 * Constructor.
	 *
	 * @param array     $options
	 * @param Input\Cli $input
	 * @param Stdout    $output
	 */
	function __construct($options, Input\Cli $input = null, Stdout $output = null)
	{
		$this->options = $options;

		parent::__construct($input, $output);
	}

	public function ask($msg = '', $default = null)
	{
		$list        = '';
		$alignSpaces = 8;

		// Count key length
		$keys    = array_keys($this->options);
		$lengths = array_map('strlen', $keys);
		$longest = max($lengths);
		$longest = $longest >= $alignSpaces ? $alignSpaces : $longest;

		// Build select list.
		foreach ($this->options as $key => $description)
		{
			$tmpl = str_replace('{WIDTH}', $longest, $this->listTemplate);

			$list .= sprintf($tmpl, ' ', $key, $description) . "\n";
		}

		$this->output->out("\n\n" . $list);

		return parent::ask($msg, $default);
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
			if (array_key_exists($value, $options))
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
 