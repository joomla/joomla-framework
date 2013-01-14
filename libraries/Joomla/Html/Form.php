<?php
/**
 * @package     Joomla.Platform
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Html;

defined('JPATH_PLATFORM') or die;

use Joomla\Session\Session;

/**
 * Utility class for form elements
 *
 * @package     Joomla.Platform
 * @subpackage  HTML
 * @since       11.1
 */
abstract class Form
{
	/**
	 * Displays a hidden token field to reduce the risk of CSRF exploits
	 *
	 * Use in conjunction with JSession::checkToken
	 *
	 * @return  string  A hidden input field with a token
	 *
	 * @see     JSession::checkToken
	 * @since   11.1
	 */
	public static function token()
	{
		return '<input type="hidden" name="' . Session::getFormToken() . '" value="1" />';
	}
}
