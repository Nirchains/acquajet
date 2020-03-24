<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
interface Swift_KeyCache_KeyCacheInputStream extends Swift_InputByteStream
{
	
	public function setKeyCache(Swift_KeyCache $keyCache);
	
	public function setNsKey($nsKey);
	
	public function setItemKey($itemKey);
	
	public function setWriteThroughStream(Swift_InputByteStream $is);
	
	public function __clone();
}