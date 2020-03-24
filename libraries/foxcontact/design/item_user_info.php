<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.html.useragentparser');

class FoxDesignItemUserInfo extends FoxDesignItem
{
	
	protected function init()
	{
		$this->set('label', JText::_('COM_FOXCONTACT_CLIENT_INFO'));
	}
	
	
	public function update(array $post_data)
	{
		$user_info_data = array();
		if ($this->get('info.device', false) || $this->get('info.os', false) || $this->get('info.browser', false))
		{
			$ua_parser = new FoxHtmlUserAgentParser(JFactory::getApplication()->input->server->get('HTTP_USER_AGENT', '', 'string'));
			if ($this->get('info.device', false))
			{
				$user_info_data['device'] = self::normalize($ua_parser->getDevice());
			}
			
			if ($this->get('info.os', false))
			{
				$user_info_data['os'] = self::normalize($ua_parser->getOS());
			}
			
			if ($this->get('info.browser', false))
			{
				$user_info_data['browser'] = self::normalize($ua_parser->getBrowser());
			}
		
		}
		
		if ($this->get('info.ip', false))
		{
			$user_info_data['ip'] = self::getCurrentIp();
		}
		
		$this->setValue($user_info_data);
	}
	
	
	public function validate(array &$messages)
	{
		return true;
	}
	
	
	public static function getCurrentIp()
	{
		return (string) JFactory::getApplication()->input->server->get('REMOTE_ADDR', '', 'string');
	}
	
	
	private static function normalize($data)
	{
		$normalized = array();
		foreach ($data as $k => $v)
		{
			$normalized[strtolower($k)] = $v !== '-' ? $v : '';
		}
		
		return $normalized;
	}
	
	
	public function getValueForUser()
	{
		return $this->getValueAsText();
	}
	
	
	public function getValueForAdmin()
	{
		return $this->getValueAsText();
	}
	
	
	public function getValueAsText()
	{
		return implode(', ', array_filter(array($this->getDeviceText(), $this->getOsText(), $this->getBrowserText(), $this->getIpText())));
	}
	
	
	public function getDeviceText()
	{
		$values = $this->getValue();
		return isset($values['device']) ? $values['device']['model'] : '';
	}
	
	
	public function getOsText()
	{
		$values = $this->getValue();
		return isset($values['os']) ? $values['os']['name'] : '';
	}
	
	
	public function getBrowserText()
	{
		$values = $this->getValue();
		return isset($values['browser']) ? "{$values['browser']['name']} {$values['browser']['major']}" : '';
	}
	
	
	public function getIpText()
	{
		if (!$this->get('info.ip', false))
		{
			return '';
		}
		
		$values = $this->getValue();
		return self::getIpAsText($values['ip']);
	}
	
	
	public static function getIpAsText($ip)
	{
		static $ip_text_cache = array();
		if (!isset($ip_text_cache[$ip]))
		{
			$ip_text_cache[$ip] = self::renderIp($ip);
		}
		
		return $ip_text_cache[$ip];
	}
	
	
	private static function renderIp($ip)
	{
		if (function_exists('geoip_record_by_name'))
		{
			$record = @geoip_record_by_name($ip) or $record = array('country_name' => '', 'city' => '', 'country_code' => '', 'region' => '');
			$country = !empty($record['country_name']) ? utf8_encode($record['country_name']) : JText::_('COM_FOXCONTACT_UNKNOWN_COUNTRY');
			$city = !empty($record['city']) ? utf8_encode($record['city']) : JText::_('COM_FOXCONTACT_UNKNOWN_LOCATION');
			$region = !empty($record['region']) ? utf8_encode(@geoip_region_name_by_code($record['country_code'], $record['region'])) : JText::_('COM_FOXCONTACT_UNKNOWN_REGION');
			$network = function_exists('geoip_asnum_by_name') ? @geoip_asnum_by_name($ip) : '';
			$network = !empty($network) ? utf8_encode($network) : JText::_('COM_FOXCONTACT_UNKNOWN_NETWORK');
			$description = JText::sprintf('COM_FOXCONTACT_LOCATION_ORIGIN', "{$country}, {$region}, {$city}, {$network}");
			$ip .= " - {$description}";
		}
		
		return $ip;
	}

}