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
class SelectPrompter extends ValidatePrompter
{
	/**
	 * Property listTemplate.
	 *
	 * @var  string
	 */
	protected $listTemplate = " %-{WIDTH}s[%s] - %s";

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
		$this->output->out("\n\n" . $this->renderList());

		return parent::ask($msg, $default);
	}

	/**
	 * renderList
	 *
	 * @return  string
	 */
	protected function renderList()
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

		return $list;
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
}
 