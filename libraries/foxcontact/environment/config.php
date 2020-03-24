<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.system.log');
use FoxContact\Log;

class FoxEnvironmentConfig
{
	
	public function run()
	{
		$table = JTable::getInstance('extension');
		$table->load(array('element' => 'com_foxcontact', 'client_id' => 1));
		$params = json_decode($table->{'params'});
		$this->testDNS($params);
		$params->mail_sender_type = 'Swift';
		$table->bind(array('params' => json_encode($params)));
		$table->check() && $table->store();
	}
	
	
	private function testDNS(&$params)
	{
		$log = Log::GetInstance();
		$log->Add('Determining if this system is able to query DNS records', 'info', 'install');
		if (!function_exists('checkdnsrr'))
		{
			$log->Add('checkdnsrr function doesn\'t exist.', 'info', 'install');
			$params->use_dns = '0';
		}
		else
		{
			$log->Add('checkdnsrr function found. Let\'s see if it works.', 'info', 'install');
			$record_found = checkdnsrr('fox.ra.it', 'MX');
			$log->Add('testing function [checkdnsrr]... [' . intval($record_found) . ']', 'info', 'install');
			$params->use_dns = $record_found ? '1' : '0';
		}
		
		$log->Add("Method chosen to query DNS records is [{$params->use_dns}]", 'info', 'install');
	}

}