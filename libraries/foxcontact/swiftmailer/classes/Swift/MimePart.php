<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_MimePart extends Swift_Mime_MimePart
{
	
	public function __construct($body = null, $contentType = null, $charset = null)
	{
		call_user_func_array(array($this, 'Swift_Mime_MimePart::__construct'), Swift_DependencyContainer::getInstance()->createDependenciesFor('mime.part'));
		if (!isset($charset))
		{
			$charset = Swift_DependencyContainer::getInstance()->lookup('properties.charset');
		}
		
		$this->setBody($body);
		$this->setCharset($charset);
		if ($contentType)
		{
			$this->setContentType($contentType);
		}
	
	}
	
	
	public static function newInstance($body = null, $contentType = null, $charset = null)
	{
		return new self($body, $contentType, $charset);
	}

}