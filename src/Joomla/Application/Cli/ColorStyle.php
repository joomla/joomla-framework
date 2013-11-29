<?php
/**
 * Part of the Joomla Framework Application Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Application\Cli;

/**
 * Class ColorStyle
 *
 * @since  1.0
 */
final class ColorStyle
{
	/**
	 * Known colors.
	 *
	 * @var    array
	 * @since  1.0
	 */
	private static $knownColors = array(
		'black'   => 0,
		'red'     => 1,
		'green'   => 2,
		'yellow'  => 3,
		'blue'    => 4,
		'magenta' => 5,
		'cyan'    => 6,
		'white'   => 7,
	);

	/**
	 * Known styles.
	 *
	 * @var    array
	 * @since  1.0
	 */
	private static $knownOptions = array(
		'bold'       => 1,
		'underscore' => 4,
		'blink'      => 5,
		'reverse'    => 7,
	);

	/**
	 * Foreground base value.
	 *
	 * @var    int
	 * @since  1.0
	 */
	private static $fgBase = 30;

	/**
	 * Background base value.
	 *
	 * @var    int
	 * @since  1.0
	 */
	private static $bgBase = 40;

	/**
	 * Foreground color.
	 *
	 * @var    int
	 * @since  1.0
	 */
	private $fgColor = 0;

	/**
	 * Background color.
	 *
	 * @var    int
	 * @since  1.0
	 */
	private $bgColor = 0;

	/**
	 * Style options.
	 *
	 * @var    array
	 * @since  1.0
	 */
	private $options = array();

	/**
	 * Constructor.
	 *
	 * @param   string  $fg       Foreground color.
	 * @param   string  $bg       Background color.
	 * @param   array   $options  Style options.
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException
	 */
	public function __construct($fg = '', $bg = '', $options = array())
	{
		if ($fg)
		{
			if (false == array_key_exists($fg, self::$knownColors))
			{
				throw new \InvalidArgumentException(
					sprintf('Invalid foreground color "%1$s" [%2$s]',
						$fg,
						implode(', ', $this->getKnownColors())
					)
				);
			}

			$this->fgColor = self::$fgBase + self::$knownColors[$fg];
		}

		if ($bg)
		{
			if (false == array_key_exists($bg, self::$knownColors))
			{
				throw new \InvalidArgumentException(
					sprintf('Invalid background color "%1$s" [%2$s]',
						$bg,
						implode(', ', $this->getKnownColors())
					)
				);
			}

			$this->bgColor = self::$bgBase + self::$knownColors[$bg];
		}

		foreach ($options as $option)
		{
			if (false == array_key_exists($option, self::$knownOptions))
			{
				throw new \InvalidArgumentException(
					sprintf('Invalid option "%1$s" [%2$s]',
						$option,
						implode(', ', $this->getKnownOptions())
					)
				);
			}

			$this->options[] = $option;
		}
	}

	/**
	 * Create a color style from a parameter string.
	 *
	 * Example: fg=red;bg=blue;options=bold,blink
	 *
	 * @param   string  $string  The parameter string.
	 *
	 * @return  ColorStyle  Returns new self
	 * @throws  \RuntimeException
	 */
	public static function fromString($string)
	{
		$fg = '';
		$bg = '';
		$options = array();

		$parts = explode(';', $string);

		foreach ($parts as $part)
		{
			$subParts = explode('=', $part);

			if (count($subParts) < 2)
			{
				continue;
			}

			switch ($subParts[0])
			{
				case 'fg':
					$fg = $subParts[1];
					break;

				case 'bg':
					$bg = $subParts[1];
					break;

				case 'options':
					$options = explode(',', $subParts[1]);
					break;

				default:
					throw new \RuntimeException('Invalid option');
					break;
			}
		}

		return new self($fg, $bg, $options);
	}

	/**
	 * Get the translated color code.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getStyle()
	{
		$values = array();

		if ($this->fgColor)
		{
			$values[] = $this->fgColor;
		}

		if ($this->bgColor)
		{
			$values[] = $this->bgColor;
		}

		foreach ($this->options as $option)
		{
			$values[] = self::$knownOptions[$option];
		}

		return implode(';', $values);
	}

	/**
	 * Convert to a string.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function __toString()
	{
		return $this->getStyle();
	}

	/**
	 * Get the known colors.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getKnownColors()
	{
		return array_keys(self::$knownColors);
	}

	/**
	 * Get the known options.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getKnownOptions()
	{
		return array_keys(self::$knownOptions);
	}
}
