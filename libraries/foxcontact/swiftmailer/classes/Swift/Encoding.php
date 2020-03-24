<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Encoding
{
	
	public static function get7BitEncoding()
	{
		return self::_lookup('mime.7bitcontentencoder');
	}
	
	
	public static function get8BitEncoding()
	{
		return self::_lookup('mime.8bitcontentencoder');
	}
	
	
	public static function getQpEncoding()
	{
		return self::_lookup('mime.qpcontentencoder');
	}
	
	
	public static function getBase64Encoding()
	{
		return self::_lookup('mime.base64contentencoder');
	}
	
	
	private static function _lookup($key)
	{
		return Swift_DependencyContainer::getInstance()->lookup($key);
	}

}