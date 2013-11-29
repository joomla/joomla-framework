<?php
/**
 * Part of the Joomla Framework Application Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Application\Cli;

/**
 * Class ColorProcessor.
 *
 * @since  1.0
 */
class ColorProcessor
{
	/**
	 * Option to use colors for output.
	 *
	 * @var    boolean
	 * @since  1.0
	 */
	public $noColors = false;

	/**
	 * Regex for style tags Lookup.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $tagFilter = '/<([a-z=;]+)>(.*?)<\/\\1>/s';

	/**
	 * Regex for style tags removal.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected static $stripFilter = '/<[\/]?[a-z=;]+>/';

	/**
	 * Processor styles.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected $styles = array();

	/**
	 * Add a style.
	 *
	 * @param   string      $name   The style name.
	 * @param   ColorStyle  $style  The color style.
	 *
	 * @return  ColorProcessor  Returns itself to support chaining.
	 *
	 * @since   1.0
	 */
	public function addStyle($name, ColorStyle $style)
	{
		$this->styles[$name] = $style;

		return $this;
	}

	/**
	 * Strip color tags from a string.
	 *
	 * @param   string  $string  The string.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public static function stripColors($string)
	{
		return preg_replace(self::$stripFilter, '', $string);
	}

	/**
	 * Process a string.
	 *
	 * @param   string  $string  The string to process.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function process($string)
	{
		preg_match_all($this->tagFilter, $string, $matches);

		if (!$matches)
		{
			return $string;
		}

		foreach ($matches[0] as $i => $m)
		{
			if (array_key_exists($matches[1][$i], $this->styles))
			{
				// A named style.

				$string = $this->replaceColors($string, $matches[1][$i], $matches[2][$i], $this->styles[$matches[1][$i]]);
			}
			elseif (strpos($matches[1][$i], '='))
			{
				// Custom format

				$string = $this->replaceColors($string, $matches[1][$i], $matches[2][$i], ColorStyle::fromString($matches[1][$i]));
			}
		}

		return $string;
	}

	/**
	 * Replace color tags in a string.
	 *
	 * @param   string      $text   The original text.
	 * @param   string      $tag    The matched tag.
	 * @param   string      $match  The match.
	 * @param   ColorStyle  $style  The color style to apply.
	 *
	 * @internal param array $matches The matching tags
	 * @return  mixed
	 *
	 * @since   1.0
	 */
	private function replaceColors($text, $tag, $match, Colorstyle $style)
	{
		$replace = $this->noColors
			? $match
			: "\033[" . $style . "m" . $match . "\033[0m";

		return str_replace('<' . $tag . '>' . $match . '</' . $tag . '>', $replace, $text);
	}
}
