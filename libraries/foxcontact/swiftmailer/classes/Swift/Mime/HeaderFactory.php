<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
interface Swift_Mime_HeaderFactory extends Swift_Mime_CharsetObserver
{
	
	public function createMailboxHeader($name, $addresses = null);
	
	public function createDateHeader($name, $timestamp = null);
	
	public function createTextHeader($name, $value = null);
	
	public function createParameterizedHeader($name, $value = null, $params = array());
	
	public function createIdHeader($name, $ids = null);
	
	public function createPathHeader($name, $path = null);
}