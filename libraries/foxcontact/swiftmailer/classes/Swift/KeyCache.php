<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
interface Swift_KeyCache
{
	const MODE_WRITE = 1;
	const MODE_APPEND = 2;
	
	public function setString($nsKey, $itemKey, $string, $mode);
	
	public function importFromByteStream($nsKey, $itemKey, Swift_OutputByteStream $os, $mode);
	
	public function getInputByteStream($nsKey, $itemKey, Swift_InputByteStream $is = null);
	
	public function getString($nsKey, $itemKey);
	
	public function exportToByteStream($nsKey, $itemKey, Swift_InputByteStream $is);
	
	public function hasKey($nsKey, $itemKey);
	
	public function clearKey($nsKey, $itemKey);
	
	public function clearAll($nsKey);
}