<?php
/**
 * Part of the Joomla Framework Database Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Mysqli;

use Joomla\Database\DatabaseExporter;

/**
 * MySQLi Database Exporter.
 *
 * @since  1.0
 */
class MysqliExporter extends DatabaseExporter
{
	/**
	 * Builds the XML data for the tables to export.
	 *
	 * @return  string  An XML string
	 *
	 * @since   1.0
	 * @throws  \Exception if an error occurs.
	 */
	protected function buildXml()
	{
		$buffer = array();

		$buffer[] = '<?xml version="1.0"?>';
		$buffer[] = '<mysqldump xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
		$buffer[] = ' <database name="">';

		$buffer = array_merge($buffer, $this->buildXmlStructure());

		$buffer[] = ' </database>';
		$buffer[] = '</mysqldump>';

		return implode("\n", $buffer);
	}

	/**
	 * Builds the XML structure to export.
	 *
	 * @return  array  An array of XML lines (strings).
	 *
	 * @since   1.0
	 * @throws  \Exception if an error occurs.
	 */
	protected function buildXmlStructure()
	{
		$buffer = array();

		foreach ($this->from as $table)
		{
			// Replace the magic prefix if found.
			$table = $this->getGenericTableName($table);

			// Get the details columns information.
			$fields = $this->db->getTableColumns($table, false);
			$keys = $this->db->getTableKeys($table);

			$buffer[] = '  <table_structure name="' . $table . '">';

			foreach ($fields as $field)
			{
				$buffer[] = '   <field Field="' . $field->Field . '"' . ' Type="' . $field->Type . '"' . ' Null="' . $field->Null . '"' . ' Key="' .
					$field->Key . '"' . (isset($field->Default) ? ' Default="' . $field->Default . '"' : '') . ' Extra="' . $field->Extra . '"' .
					' />';
			}

			foreach ($keys as $key)
			{
				$buffer[] = '   <key Table="' . $table . '"' . ' Non_unique="' . $key->Non_unique . '"' . ' Key_name="' . $key->Key_name . '"' .
					' Seq_in_index="' . $key->Seq_in_index . '"' . ' Column_name="' . $key->Column_name . '"' . ' Collation="' . $key->Collation . '"' .
					' Null="' . $key->Null . '"' . ' Index_type="' . $key->Index_type . '"' . ' Comment="' . htmlspecialchars($key->Comment) . '"' .
					' />';
			}

			$buffer[] = '  </table_structure>';
		}

		return $buffer;
	}

	/**
	 * Checks if all data and options are in order prior to exporting.
	 *
	 * @return  MysqliExporter  Method supports chaining.
	 *
	 * @since   1.0
	 * @throws  \Exception if an error is encountered.
	 */
	public function check()
	{
		// Check if the db connector has been set.
		if (!($this->db instanceof MysqliDriver))
		{
			throw new \Exception('Database connection wrong type.');
		}

		// Check if the tables have been specified.
		if (empty($this->from))
		{
			throw new \Exception('ERROR: No Tables Specified');
		}

		return $this;
	}
}
