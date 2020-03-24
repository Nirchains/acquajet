<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Validate
{
	private static $grammar = null;
	
	public static function email($email)
	{
		if (self::$grammar === null)
		{
			self::$grammar = Swift_DependencyContainer::getInstance()->lookup('mime.grammar');
		}
		
		return (bool) preg_match('/^' . self::$grammar->getDefinition('addr-spec') . '$/D', $email);
	}

}