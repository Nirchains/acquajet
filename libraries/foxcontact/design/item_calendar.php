<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.html.resource');

class FoxDesignItemCalendar extends FoxDesignItem
{
	
	public function addResources(JDocument $document)
	{
		FoxHtmlResource::NewInstance()->Add('/media/com_foxcontact/js/calendar', 'js')->Add('/media/com_foxcontact/css/calendar', 'css')->ToDocument($document);
	}
	
	
	public function hasDate()
	{
		return $this->get('mode') === 'datetime' || $this->get('mode') === 'date';
	}
	
	
	public function hasTime()
	{
		return $this->get('mode') === 'datetime' || $this->get('mode') === 'time';
	}
	
	
	public function hasDateAndTime()
	{
		return $this->get('mode') === 'datetime';
	}
	
	
	public function getFormat()
	{
		return ($this->hasDate() ? JText::_('DATE_FORMAT_LC') : '') . ($this->hasTime() ? ' H:i' : '');
	}
	
	
	public function getOptions()
	{
		$options = array('theme' => $this->get('theme', 'default'), 'datepicker' => $this->hasDate(), 'timepicker' => $this->hasTime(), 'closeOnDateSelect' => !$this->hasDateAndTime(), 'step' => (int) $this->get('step', 60));
		return json_encode($options);
	}

}