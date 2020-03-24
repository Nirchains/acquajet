<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
interface Swift_Transport_IoBuffer extends Swift_InputByteStream, Swift_OutputByteStream
{
	const TYPE_SOCKET = 1;
	const TYPE_PROCESS = 16;
	
	public function initialize(array $params);
	
	public function setParam($param, $value);
	
	public function terminate();
	
	public function setWriteTranslations(array $replacements);
	
	public function readLine($sequence);
}