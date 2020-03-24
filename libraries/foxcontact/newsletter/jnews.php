<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.system.log');
jimport('foxcontact.newsletter.driver');
use FoxContact\Log;

class FoxNewsletterJNewsDriver extends FoxNewsletterExtensionDriver
{
	
	public function getType()
	{
		return 'jnews';
	}
	
	
	protected function config()
	{
		$configured = true;
		defined('JNEWS_JPATH_ROOT') or define('JNEWS_JPATH_ROOT', JPATH_ROOT);
		$configured &= (bool) @(include_once JPATH_ROOT . '/components/com_jnews/defines.php');
		if (defined('JNEWS_OPTION'))
		{
			$configured &= (bool) @(include_once JNEWS_JPATH_ROOT . '/administrator/components/' . JNEWS_OPTION . '/classes/class.jnews.php');
		}
		else
		{
			$configured = false;
		}
		
		return $configured;
	}
	
	
	protected function _load(array $ids)
	{
		return $this->query('id', 'list_name', '#__jnews_lists', 'published', 'id', $ids);
	}
	
	
	protected function _subscribe(array $ids, $name, $email)
	{
		if (!class_exists('jNews_Config'))
		{
			return;
		}
		
		$config = new jNews_Config();
		$subscriber = new stdClass();
		$subscriber->list_id = $ids;
		$subscriber->name = $name;
		$subscriber->email = $email;
		if (empty($subscriber->email))
		{
			return;
		}
		
		$subscriber->confirmed = !(bool) $config->get('require_confirmation');
		$subscriber->receive_html = 1;
		$subscriber->ip = jNews_Subscribers::getIP();
		$subscriber->subscribe_date = jnews::getNow();
		$subscriber->language_iso = 'eng';
		$subscriber->timezone = '00:00:00';
		$subscriber->blacklist = 0;
		$subscriber->user_id = JFactory::getUser()->id;
		$sub_id = 0;
		jNews_Subscribers::saveSubscriber($subscriber, $sub_id, true);
		if (empty($sub_id))
		{
			Log::GetInstance()->Add("Unable to save the user to the newsletter ({$this->getType()}): User (Name: '{$name}' Email: '{$email}')", 'info', 'action');
			return;
		}
		
		$subscriber->id = $sub_id;
		jNews_ListsSubs::saveToListSubscribers($subscriber);
		Log::GetInstance()->Add('Newsletter (' . JText::_("COM_FOXCONTACT_ITEM_NEWSLETTER_{$this->getType()}_LBL") . "): User (Name: '{$name}' Email: '{$email}') subscribed to the lists (" . implode(',', $ids) . ').', 'info', 'action');
	}

}