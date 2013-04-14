<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Tests;

/**
 * Class to expose protected properties and methods in JDatabaseMySqlExporter for testing purposes.
 *
 * @since  1.0
 */
class ImporterMySqlInspector extends \Joomla\Database\Mysql\MysqlImporter
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
	 * Exposes the protected check method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function check()
	{
		return parent::check();
	}

	/**
	 * Exposes the protected getAddColumnSQL method.
	 *
	 * @param   string            $table  The table name.
	 * @param   SimpleXMLElement  $field  The XML field definition.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getAddColumnSQL($table, \SimpleXMLElement $field)
	{
		return parent::getAddColumnSQL($table, $field);
	}

	/**
	 * Exposes the protected getAddKeySQL method.
	 *
	 * @param   string  $table  The table name.
	 * @param   array   $keys   An array of the fields pertaining to this key.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getAddKeySQL($table, $keys)
	{
		return parent::getAddKeySQL($table, $keys);
	}

	/**
	 * Exposes the protected getAlterTableSQL method.
	 *
	 * @param   SimpleXMLElement  $structure  The structure.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getAlterTableSQL(\SimpleXMLElement $structure)
	{
		return parent::getAlterTableSQL($structure);
	}

	/**
	 * Exposes the protected getChangeColumnSQL method.
	 *
	 * @param   string            $table  Table name.
	 * @param   SimpleXMLElement  $field  The field.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getChangeColumnSQL($table, \SimpleXMLElement $field)
	{
		return parent::getChangeColumnSQL($table, $field);
	}

	/**
	 * Exposes the protected getColumnSQL method.
	 *
	 * @param   SimpleXMLElement  $field  The XML field definition.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getColumnSQL(\SimpleXMLElement $field)
	{
		return parent::getColumnSQL($field);
	}

	/**
	 * Exposes the protected getDropColumnSQL method.
	 *
	 * @param   string  $table  The table name.
	 * @param   string  $name   The name of the field to drop.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getDropColumnSQL($table, $name)
	{
		return parent::getDropColumnSQL($table, $name);
	}

	/**
	 * Exposes the protected getDropKeySQL method.
	 *
	 * @param   string  $table  The table name.
	 * @param   string  $name   @todo
	 *
	 * @internal param string $field The name of the key to drop.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getDropKeySQL($table, $name)
	{
		return parent::getDropKeySQL($table, $name);
	}

	/**
	 * Exposes the protected getDropPrimaryKeySQL method.
	 *
	 * @param   string  $table  The table name.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getDropPrimaryKeySQL($table)
	{
		return parent::getDropPrimaryKeySQL($table);
	}

	/**
	 * Exposes the protected getKeyLookup method.
	 *
	 * @param   array  $keys  An array of objects that comprise the keys for the table.
	 *
	 * @return  array The lookup array. array({key name} => array(object, ...))
	 *
	 * @since   1.0
	 * @throws	Exception
	 */
	public function getKeyLookup($keys)
	{
		return parent::getKeyLookup($keys);
	}

	/**
	 * Exposes the protected getKeySQL method.
	 *
	 * @param   array  $columns  An array of SimpleXMLElement objects comprising the key.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getKeySQL($columns)
	{
		return parent::getKeySQL($columns);
	}

	/**
	 * Exposes the protected getRealTableName method.
	 *
	 * @param   string  $table  The name of the table.
	 *
	 * @return  string	The real name of the table.
	 *
	 * @since   1.0
	 */
	public function getRealTableName($table)
	{
		return parent::getRealTableName($table);
	}

	/**
	 * Exposes the protected mergeStructure method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  Exception on error.
	 */
	public function mergeStructure()
	{
		return parent::mergeStructure();
	}

	/**
	 * Exposes the protected withStructure method.
	 *
	 * @param   boolean	  $setting  True to export the structure, false to not.
	 *
	 * @return  void
	 *
	 * @since	1.0
	 */
	public function withStructure($setting = true)
	{
		return parent::withStructure($setting);
	}
}
