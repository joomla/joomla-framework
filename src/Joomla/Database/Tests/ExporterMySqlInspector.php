<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Tests;

/**
 * Class to expose protected properties and methods in \Joomla\Database\Exporter\Mysql for testing purposes.
 *
 * @since  1.0
 */
class ExporterMySqlInspector extends \Joomla\Database\Mysql\MysqlExporter
{
	/**
	 * Gets any property from the class.
	 *
	 * @param   string  $property  The name of the class property.
	 *
	 * @return  mixed   The value of the class property.
	 *
	 * @since   1.0
	 */
	public function __get($property)
	{
		return $this->$property;
	}

	/**
	 * Exposes the protected buildXml method.
	 *
	 * @return  string  An XML string
	 *
	 * @since   1.0
	 * @throws  Exception if an error occurs.
	 */
	public function buildXml()
	{
		return parent::buildXml();
	}

	/**
	 * Exposes the protected buildXmlStructure method.
	 *
	 * @return  array  An array of XML lines (strings).
	 *
	 * @since   1.0
	 * @throws  \Exception if an error occurs.
	 */
	public function buildXmlStructure()
	{
		return parent::buildXmlStructure();
	}

	/**
	 * Exposes the protected getGenericTableName method.
	 *
	 * @param   string  $table  The name of a table.
	 *
	 * @return  string  The name of the table with the database prefix replaced with #__.
	 *
	 * @since   1.0
	 */
	public function getGenericTableName($table)
	{
		return parent::getGenericTableName($table);
	}
}
