<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

abstract class Swift
{
	const VERSION = '@SWIFT_VERSION_NUMBER@';
	public static $initialized = false;
	public static $inits = array();
	
	public static function init($callable)
	{
		self::$inits[] = $callable;
	}
	
	
	public static function autoload($class)
	{
		if (0 !== strpos($class, 'Swift_'))
		{
			return;
		}
		
		$path = __DIR__ . '/' . str_replace('_', '/', $class) . '.php';
		if (!file_exists($path))
		{
			return;
		}
		
		require $path;
		if (self::$inits && !self::$initialized)
		{
			self::$initialized = true;
			foreach (self::$inits as $init)
			{
				call_user_func($init);
			}
		
		}
	
	}
	
	
	public static function registerAutoload($callable = null)
	{
		if (null !== $callable)
		{
			self::$inits[] = $callable;
		}
		
		spl_autoload_register(array('Swift', 'autoload'));
	}

}