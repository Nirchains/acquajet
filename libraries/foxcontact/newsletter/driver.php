<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
interface FoxNewsletterDriver
{
	
	public function getType();
	
	public function isEnable();
	
	public function load(array $ids);
	
	public function subscribe(array $ids, $name, $email);
}

class FoxNewsletterDummyDriver implements FoxNewsletterDriver
{
	
	public function getType()
	{
		return 'dummy';
	}
	
	
	public function isEnable()
	{
		return false;
	}
	
	
	public function load(array $ids)
	{
		return null;
	}
	
	
	public function subscribe(array $ids, $name, $email)
	{
	}

}


abstract class FoxNewsletterExtensionDriver implements FoxNewsletterDriver
{
	private $enable, $info = false;
	
	public function __construct()
	{
		$this->enable = $this->isInstalled() && $this->config();
	}
	
	
	public function isEnable()
	{
		return $this->enable;
	}
	
	
	private function isInstalled()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('extension_id'));
		$query->from($db->quoteName('#__extensions'));
		$query->where("{$db->quoteName('name')} = {$db->quote($this->getType())}");
		$db->setQuery($query);
		return (bool) $db->loadResult();
	}
	
	
	protected abstract function config();
	
	public function load(array $ids)
	{
		if ($this->info === false)
		{
			$this->info = $this->_load($ids);
		}
		
		return $this->info;
	}
	
	
	protected abstract function _load(array $ids);
	
	protected function query($key, $value, $table, $published, $order, array $ids)
	{
		$options = array();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("{$db->quoteName($key)},{$db->quoteName($value)}");
		$query->from($db->quoteName($table));
		$query->where("{$db->quoteName($published)} = {$db->quote('1')}");
		$query->order("{$db->quoteName($order)} ASC");
		if (count($ids) > 0)
		{
			foreach ($ids as $k => $filter)
			{
				$ids[$k] = $db->quote($filter);
			}
			
			$ids_as_string = implode(',', $ids);
			$query->where("{$db->quoteName($key)} IN ({$ids_as_string})");
		}
		
		$db->setQuery($query);
		$items = $db->loadObjectList() or $items = array();
		if (count($items) === 0)
		{
			return null;
		}
		
		foreach ($items as $item)
		{
			$options[] = array('value' => $item->{$key}, 'label' => $item->{$value});
		}
		
		return array('type' => $this->getType(), 'name' => JText::_("COM_FOXCONTACT_ITEM_NEWSLETTER_{$this->getType()}_LBL"), 'options' => $options);
	}
	
	
	public function subscribe(array $ids, $name, $email)
	{
		$ids = $this->getIdsAsInt($ids);
		$email = JMailHelper::cleanAddress($email);
		if (!empty($ids) && !empty($email))
		{
			$this->_subscribe($ids, $name, $email);
		}
	
	}
	
	
	protected abstract function _subscribe(array $ids, $name, $email);
	
	private function getIdsAsInt(array $array)
	{
		$result = array();
		foreach ($array as $id)
		{
			$result[] = (int) $id;
		}
		
		return $result;
	}

}