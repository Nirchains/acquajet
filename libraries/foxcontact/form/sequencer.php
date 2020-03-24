<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

abstract class FoxSequencer
{
	private $series;
	
	public function __construct($series)
	{
		$this->series = $series;
		$db = JFactory::getDbo();
		$db->setQuery("INSERT IGNORE INTO {$db->quoteName('#__foxcontact_sequences')} ({$db->quoteName('series')}) VALUES ({$db->quote($this->series)});")->execute();
	}
	
	
	public function getNextValue()
	{
		$db = JFactory::getDbo();
		$db->setQuery($db->getQuery(true)->update($db->quoteName('#__foxcontact_sequences'))->set($db->quoteName('value') . ' = LAST_INSERT_ID(' . $db->quoteName('value') . ' + 1)')->where("{$db->quoteName('series')} = {$db->quote($this->series)}"))->execute();
		$next_value = $db->insertid();
		return strtoupper($this->convert($next_value) . (empty($this->series) ? '' : '-') . $this->series);
	}
	
	
	protected abstract function convert($value);
}


class FoxSequencerA extends FoxSequencer
{
	
	protected function convert($value)
	{
		$result = '';
		while ($value > 0)
		{
			$mod = ($value - 1) % 26;
			$result = chr(65 + $mod) . $result;
			$value = (int) (($value - $mod) / 26);
		}
		
		return $result;
	}

}


class FoxSequencerN extends FoxSequencer
{
	
	protected function convert($value)
	{
		return "{$value}";
	}

}


class FoxSequencerAN extends FoxSequencer
{
	
	protected function convert($value)
	{
		return strtoupper(base_convert((int) $value, 10, 36));
	}

}