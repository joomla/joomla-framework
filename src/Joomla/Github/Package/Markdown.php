<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Package;

use Joomla\Github\Package;

/**
 * GitHub API Markdown class.
 *
 * @documentation http://developer.github.com/v3/markdown
 *
 * @package     Joomla.Platform
 * @subpackage  GitHub.Markdown
 * @since       ¿
 */
class Markdown extends Package
{
	/**
	 * Method to render a markdown document.
	 *
	 * @param   string  $text     The text object being parsed.
	 * @param   string  $mode     The parsing mode; valid options are 'markdown' or 'gfm'.
	 * @param   string  $context  An optional repository context, only used in 'gfm' mode.
	 *
	 * @throws \InvalidArgumentException
	 * @since   ¿
	 * @return  string  Formatted HTML
	 */
	public function render($text, $mode = 'gfm', $context = null)
	{
		// The valid modes
		$validModes = array('gfm', 'markdown');

		// Make sure the scope is valid
		if (!in_array($mode, $validModes))
		{
			throw new \InvalidArgumentException(sprintf('The %s mode is not valid. Valid modes are "gfm" or "markdown".', $mode));
		}

		// Build the request path.
		$path = '/markdown';

		// Build the request data.
		$data = str_replace('\\/', '/', json_encode(
				array(
					'text'    => $text,
					'mode'    => $mode,
					'context' => $context
				)
			)
		);

		// Send the request.
		$response = $this->client->post($this->fetchUrl($path), $data);

		// Validate the response code.
		if ($response->code != 200)
		{
			// Decode the error response and throw an exception.
			$error = json_decode($response->body);
			$message = (isset($error->message)) ? $error->message : 'Error: ' . $response->code;
			throw new \DomainException($message, $response->code);
		}

		return $response->body;
	}
}
