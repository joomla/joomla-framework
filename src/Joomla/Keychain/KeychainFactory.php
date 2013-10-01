<?php
/**
 * Part of the Joomla Framework Keychain Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Keychain;

use Joomla\Keychain\Keychain;
use Joomla\Registry\Format\IniFormat;
use Joomla\Registry\Format\JsonFormat;
use Joomla\Registry\Format\PhpFormat;
use Joomla\Registry\Format\XmlFormat;
use Joomla\Registry\Format\YamlFormat;

use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Yaml\Dumper as YamlDumper;

/**
 * Factory for the Keychain Package
 *
 * @since  1.0
 */
class KeychainFactory
{
	/**
	 * Returns a Keychain object with all default Formats.
	 *
	 * @return  Keychain  The Keychain object.
	 *
	 * @since   1.0
	 */
	public static function getKeychain()
	{
		$instance = new Keychain;
		$instance->registerFormat(new IniFormat);
		$instance->registerFormat(new JsonFormat);
		$instance->registerFormat(new PhpFormat);
		$instance->registerFormat(new XmlFormat);

		if (class_exists('Symfony\Component\Yaml\Parser') && class_exists('Symfony\Component\Yaml\Dumper'))
		{
			$instance->registerFormat(new YamlFormat(new YamlParser, new YamlDumper));
		}

		return $instance;
	}
}
