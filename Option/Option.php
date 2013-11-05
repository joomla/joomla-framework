<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Option;

use Joomla\Input\Cli as Input;

/**
 * The cli option class.
 *
 * @since  1.0
 */
class Option
{
	const IS_GLOBAL = true;

	const IS_NORMAL = false;

	/**
	 * Option name.
	 *
	 * @var  string
	 */
	protected $name;

	/**
	 * Option alias.
	 *
	 * @var  array
	 */
	protected $alias = array();

	/**
	 * Option description.
	 *
	 * @var  string
	 */
	protected $description;

	/**
	 * Global option or not.
	 *
	 * @var  boolean
	 */
	protected $global;

	/**
	 * The default when option not sent.
	 *
	 * @var  string
	 */
	protected $default;

	/**
	 * Cli Input object.
	 *
	 * @var Input
	 */
	protected $input;

	/**
	 * The option value cache.
	 *
	 * @var string
	 */
	protected $value;

	/**
	 * Class Constructor.
	 *
	 * @param   mixed    $alias        The option name. Can be a string, an array or an object.
	 *                                  If we use array, the first element will be option name, others will be alias.
	 * @param   mixed    $default      The default value when we get a non-exists option.
	 * @param   string   $description  The option description.
	 * @param   boolean  $global       True is a global option.
	 */
	public function __construct($alias, $default = null, $description = null, $global = false)
	{
		$alias = (array) $alias;
		$name  = array_shift($alias);

		$this->name        = $name;
		$this->default     = $default;
		$this->description = $description;
		$this->global      = $global;

		if (count($alias))
		{
			$this->setAlias($alias);
		}
	}

	/**
	 * Alias setter.
	 *
	 * @param   string  $alias  The option alias.
	 *
	 * @return  Option  Return this object to support chaining.
	 */
	public function setAlias($alias)
	{
		$this->alias = $alias;

		return $this;
	}

	/**
	 * Alias getter.
	 *
	 * @return array  The option alias.
	 */
	public function getAlias()
	{
		return $this->alias;
	}

	/**
	 * Default value getter.
	 *
	 * @param   mixed  $default  The default value.
	 *
	 * @return  Option  Return this object to support chaining.
	 */
	public function setDefault($default)
	{
		$this->default = $default;

		return $this;
	}

	/**
	 * Default value getter.
	 *
	 * @return string  The default value.
	 */
	public function getDefault()
	{
		return $this->default;
	}

	/**
	 * Description setter.
	 *
	 * @param   string  $description  The description.
	 *
	 * @return  Option  Return this object to support chaining.
	 */
	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * Description getter.
	 *
	 * @return  string  The description.
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Name setter.
	 *
	 * @param   string  $name  Name of this option.
	 *
	 * @return  Option  Return this object to support chaining.
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Name getter.
	 *
	 * @return  string  Name of this option.
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get Cli Input object.
	 *
	 * @return  Input  The Cli Input object.
	 */
	public function getInput()
	{
		if (!$this->input)
		{
			$this->input = new Input;
		}

		return $this->input;
	}

	/**
	 * Set Cli Input object.
	 *
	 * @param   Input  $input  The Cli Input object.
	 *
	 * @return  Option  Return this object to support chaining.
	 */
	public function setInput(Input $input)
	{
		$this->input = $input;

		return $this;
	}

	/**
	 * Get the value of this option which sent from command line.
	 *
	 * @return  mixed  The value of this option.
	 */
	public function getValue()
	{
		$input = $this->getInput();

		$name = $this->name;

		if ($input->get($name))
		{
			return $input->get($name);
		}

		foreach ($this->alias as $alias)
		{
			if ($input->get($alias))
			{
				return $input->get($alias);
			}
		}

		return $this->default;
	}

	/**
	 * Is this a global option?
	 *
	 * @return  bool  True is a global option.
	 */
	public function isGlobal()
	{
		return $this->global;
	}

	/**
	 * Set this option is global or not.
	 *
	 * @param   boolean  $global  True is a global option.
	 *
	 * @return  Option  Return this object to support chaining.
	 */
	public function setGlobal($global)
	{
		$this->global = $global;

		return $this;
	}
}
