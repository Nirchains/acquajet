<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
$preferences = Swift_Preferences::getInstance();
$preferences->setCharset('utf-8');
if (@is_writable($tmpDir = sys_get_temp_dir()))
{
	$preferences->setTempDir($tmpDir)->setCacheType('disk');
}

if (PHP_VERSION_ID < 50407)
{
	$preferences->setQPDotEscape(false);
}