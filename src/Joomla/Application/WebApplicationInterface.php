<?php
/**
 * Part of the Joomla Framework Archive Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Application;

/**
 * Application class interface
 *
 * @since  1.0
 */
interface WebApplicationInterface
{
	/**
	 * Execute the application.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function execute();

	/**
	 * Redirect to another URL.
	 *
	 * If the headers have not been sent the redirect will be accomplished using a "301 Moved Permanently"
	 * or "303 See Other" code in the header pointing to the new location. If the headers have already been
	 * sent this will be accomplished using a JavaScript statement.
	 *
	 * @param   string   $url    The URL to redirect to. Can only be http/https URL
	 * @param   boolean  $moved  True if the page is 301 Permanently Moved, otherwise 303 See Other is assumed.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function redirect($url, $moved = false);

	/**
	 * Set/get cachable state for the response.  If $allow is set, sets the cachable state of the
	 * response.  Always returns the current state.
	 *
	 * @param   boolean  $allow  True to allow browser caching.
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function allowCache($allow = null);

	/**
	 * Method to set a response header.  If the replace flag is set then all headers
	 * with the given name will be replaced by the new one.  The headers are stored
	 * in an internal array to be sent when the site is sent to the browser.
	 *
	 * @param   string   $name     The name of the header to set.
	 * @param   string   $value    The value of the header to set.
	 * @param   boolean  $replace  True to replace any headers with the same name.
	 *
	 * @return  AbstractWebApplication  Instance of $this to allow chaining.
	 *
	 * @since   1.0
	 */
	public function setHeader($name, $value, $replace = false);

	/**
	 * Method to get the array of response headers to be sent when the response is sent
	 * to the client.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getHeaders();

	/**
	 * Method to clear any set response headers.
	 *
	 * @return  AbstractWebApplication  Instance of $this to allow chaining.
	 *
	 * @since   1.0
	 */
	public function clearHeaders();

	/**
	 * Send the response headers.
	 *
	 * @return  AbstractWebApplication  Instance of $this to allow chaining.
	 *
	 * @since   1.0
	 */
	public function sendHeaders();

	/**
	 * Set body content.  If body content already defined, this will replace it.
	 *
	 * @param   string  $content  The content to set as the response body.
	 *
	 * @return  AbstractWebApplication  Instance of $this to allow chaining.
	 *
	 * @since   1.0
	 */
	public function setBody($content);

	/**
	 * Prepend content to the body content
	 *
	 * @param   string  $content  The content to prepend to the response body.
	 *
	 * @return  AbstractWebApplication  Instance of $this to allow chaining.
	 *
	 * @since   1.0
	 */
	public function prependBody($content);

	/**
	 * Append content to the body content
	 *
	 * @param   string  $content  The content to append to the response body.
	 *
	 * @return  AbstractWebApplication  Instance of $this to allow chaining.
	 *
	 * @since   1.0
	 */
	public function appendBody($content);

	/**
	 * Return the body content
	 *
	 * @param   boolean  $asArray  True to return the body as an array of strings.
	 *
	 * @return  mixed  The response body either as an array or concatenated string.
	 *
	 * @since   1.0
	 */
	public function getBody($asArray = false);

	/**
	 * Method to get the application session object.
	 *
	 * @return  Session  The session object
	 *
	 * @since   1.0
	 */
	public function getSession();

	/**
	 * Determine if we are using a secure (SSL) connection.
	 *
	 * @return  boolean  True if using SSL, false if not.
	 *
	 * @since   1.0
	 */
	public function isSSLConnection();

}
