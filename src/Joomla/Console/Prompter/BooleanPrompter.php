<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Prompter;

/**
 * Class BooleanPrompter
 *
 * @since 1.0
 */
class BooleanPrompter extends TextPrompter
{
	/**
	 * Property trueAlias.
	 *
	 * @var  array
	 */
	protected $trueAlias = array('y', 'yes', 1);

	/**
	 * Property falseAlias.
	 *
	 * @var  array
	 */
	protected $falseAlias = array('n', 'no', 0, 'null');

	/**
	 * Property attempt.
	 *
	 * @var  int
	 */
	protected $attempt = 1;

	/**
	 * ask
	 *
	 * @param string $msg
	 * @param null   $default
	 *
	 * @return  bool|mixed
	 */
	public function ask($msg = '', $default = null)
	{
		$value = parent::ask($msg, $default);

		if (is_null($value))
		{
			return $value;
		}

		$value = strtolower($value);

		if (in_array($value, $this->trueAlias))
		{
			return true;
		}
		elseif (in_array($value, $this->falseAlias))
		{
			return false;
		}

		return $default;
	}

	/**
	 * getTrueAlias
	 *
	 * @return  array
	 */
	public function getTrueAlias()
	{
		return $this->trueAlias;
	}

	/**
	 * setTrueAlias
	 *
	 * @param   array $trueAlias
	 *
	 * @return  BooleanPrompter  Return self to support chaining.
	 */
	public function setTrueAlias($trueAlias)
	{
		$this->trueAlias = $trueAlias;

		return $this;
	}

	/**
	 * getFalseAlias
	 *
	 * @return  array
	 */
	public function getFalseAlias()
	{
		return $this->falseAlias;
	}

	/**
	 * setFalseAlias
	 *
	 * @param   array $falseAlias
	 *
	 * @return  BooleanPrompter  Return self to support chaining.
	 */
	public function setFalseAlias($falseAlias)
	{
		$this->falseAlias = $falseAlias;

		return $this;
	}
}
 