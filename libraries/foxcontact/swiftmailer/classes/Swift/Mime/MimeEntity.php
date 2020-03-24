<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
interface Swift_Mime_MimeEntity extends Swift_Mime_CharsetObserver, Swift_Mime_EncodingObserver
{
	const LEVEL_TOP = 16;
	const LEVEL_MIXED = 256;
	const LEVEL_ALTERNATIVE = 4096;
	const LEVEL_RELATED = 65536;
	
	public function getNestingLevel();
	
	public function getContentType();
	
	public function getId();
	
	public function getChildren();
	
	public function setChildren(array $children);
	
	public function getHeaders();
	
	public function getBody();
	
	public function setBody($body, $contentType = null);
	
	public function toString();
	
	public function toByteStream(Swift_InputByteStream $is);
}