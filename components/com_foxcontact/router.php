<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class FoxContactRouter extends JComponentRouterBase
{
	
	public function build(&$query)
	{
		$segments = array();
		$keys = array();
		foreach ($query as $key => $val)
		{
			if ($key !== 'option' && $key !== 'Itemid' && $key !== 'lang')
			{
				$keys[] = $key;
			}
		
		}
		
		sort($keys);
		foreach ($keys as $key)
		{
			switch ($key)
			{
				case 'view':
					if (!isset($query['Itemid']) || empty($query['Itemid']))
					{
						$segments[] = (string) $key;
						$segments[] = (string) $query[$key];
					}
					
					break;
				default:
					if (strlen($query[$key]) > 0)
					{
						$segments[] = (string) $key;
						$segments[] = (string) $query[$key];
					}
			
			}
			
			unset($query[$key]);
		}
		
		return $segments;
	}
	
	
	public function parse(&$segments)
	{
		$active_menu = JFactory::getApplication()->getMenu()->getActive();
		$vars = !is_null($active_menu) ? $active_menu->query : array();
		if (count($segments) % 2 !== 0)
		{
			throw new Exception(JText::_('JERROR_PAGE_NOT_FOUND'), 404);
		}
		
		$segments = array_values($segments);
		for ($i = 0, $len = count($segments); $i < $len; $i += 2)
		{
			$vars[self::clean($segments[$i])] = self::clean($segments[$i + 1]);
		}
		
		return $vars;
	}
	
	
	private static function clean($value)
	{
		return (string) preg_replace('/[^A-Z0-9_\\.-]/i', '', $value);
	}

}

function FoxContactBuildRoute(array &$query)
{
	$router = new FoxContactRouter();
	return $router->build($query);
}
function FoxContactParseRoute(array &$segments)
{
	$router = new FoxContactRouter();
	return $router->parse($segments);
}