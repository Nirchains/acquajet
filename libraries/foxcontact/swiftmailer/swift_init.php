<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
if (defined('SWIFT_INIT_LOADED'))
{
	return;
}

define('SWIFT_INIT_LOADED', true);
require __DIR__ . '/dependency_maps/cache_deps.php';
require __DIR__ . '/dependency_maps/mime_deps.php';
require __DIR__ . '/dependency_maps/message_deps.php';
require __DIR__ . '/dependency_maps/transport_deps.php';
require __DIR__ . '/preferences.php';