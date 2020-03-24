<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
if (class_exists('Swift', false))
{
	return;
}

require __DIR__ . '/classes/Swift.php';
if (!function_exists('_swiftmailer_init'))
{
	function _swiftmailer_init()
	{
		require __DIR__ . '/swift_init.php';
	}
}

Swift::registerAutoload('_swiftmailer_init');