<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.newsletter.driver');

class FoxFormNewsletter
{
	private static $drivers = array();
	
	private static function getDriver($newsletter)
	{
		if (!isset(self::$drivers[$newsletter]))
		{
			jimport("foxcontact.newsletter.{$newsletter}");
			$class_name = "FoxNewsletter{$newsletter}Driver";
			self::$drivers[$newsletter] = !class_exists($class_name) ? new FoxNewsletterDummyDriver() : new $class_name();
		}
		
		return self::$drivers[$newsletter];
	}
	
	
	public static function loadAll()
	{
		$newsletters = array();
		foreach (array('acymailing', 'acym', 'jnews') as $newsletter)
		{
			$newsletters[] = self::load($newsletter);
		}
		
		return array_values(array_filter($newsletters));
	}
	
	
	public static function load($newsletter, $ids = array())
	{
		$driver = self::getDriver($newsletter);
		return $driver->isEnable() ? $driver->load($ids) : null;
	}
	
	
	public static function subscribe($newsletter, $ids, $name, $email)
	{
		$driver = self::getDriver($newsletter);
		if ($driver->isEnable())
		{
			$driver->subscribe($ids, $name, $email);
		}
	
	}

}