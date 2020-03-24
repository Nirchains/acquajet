<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.system.log');
use FoxContact\Log;

class FoxDesignItemEmail extends FoxDesignItem
{
	
	protected function getDefaultValue()
	{
		$user = JFactory::getUser();
		return !$user->guest && $this->get('autofill', true) ? $user->email : null;
	}
	
	
	public function setValue($value)
	{
		parent::setValue(trim($value));
	}
	
	
	protected function check($value, array &$messages)
	{
		parent::check($value, $messages);
		if (!$this->isValueEmpty($value))
		{
			$tokens = explode('@', $value);
			$domain = array_pop($tokens);
			if (!$this->isValidEmailByRegex($value) || !$this->isValidEmailByDns($domain) || $this->domainBlacklisted($domain) || JMailHelper::cleanAddress($value) === false)
			{
				$messages[] = $this->getMessage(JText::sprintf('COM_FOXCONTACT_ERR_INVALID_VALUE', $this->get('label')));
			}
		
		}
	
	}
	
	
	private function isValidEmailByRegex($value)
	{
		return preg_match('/^[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,63}$/', strtolower($value)) === 1;
	}
	
	
	private function isValidEmailByDns($domain)
	{
		if ((bool) JComponentHelper::getParams('com_foxcontact')->get('use_dns'))
		{
			return checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A');
		}
		
		return true;
	}
	
	
	private function domainBlacklisted($domain)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)->select('1')->from($db->quoteName('#__foxcontact_domain_blacklist'))->where("{$db->quoteName('domain')} = {$db->quote($domain)}");
		$blacklisted = (bool) $db->setQuery($query, 0, 1)->loadResult();
		if ($blacklisted)
		{
			Log::GetInstance()->Add("Failed email validation, blacklisted domain: {$domain}", 'debug', 'form');
		}
		
		return $blacklisted;
	}

}