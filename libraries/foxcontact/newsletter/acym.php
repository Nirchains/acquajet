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

class FoxNewsletterAcyMDriver extends FoxNewsletterExtensionDriver
{
	
	public function getType()
	{
		return 'acym';
	}
	
	
	protected function config()
	{
		return (bool) @(include_once JPATH_ADMINISTRATOR . '/components/com_acym/helpers/helper.php');
	}
	
	
	protected function _load(array $ids)
	{
		return $this->query('id', 'name', '#__acym_list', 'active', 'id', $ids);
	}
	
	
	protected function _subscribe(array $ids, $name, $email)
	{
		$subscriber = new stdClass();
		$subscriber->name = $name;
		$subscriber->email = $email;
		$user = acym_get('class.user');
		$user->checkVisitor = false;
		$already_exists = $user->getOneByEmail($subscriber->email);
		if (!is_null($already_exists))
		{
			$subscriber->id = $already_exists->id;
		}
		
		$subscriber->id = $user->save($subscriber);
		if (empty($subscriber->id))
		{
			Log::GetInstance()->Add("Unable to save the user to the newsletter ({$this->getType()}): User (Name: '{$name}' Email: '{$email}')", 'info', 'action');
			return;
		}
		
		$user->subscribe($subscriber->id, $ids);
		Log::GetInstance()->Add('Newsletter (' . JText::_("COM_FOXCONTACT_ITEM_NEWSLETTER_{$this->getType()}_LBL") . "): User (Name: '{$name}' Email: '{$email}') subscribed to the lists (" . implode(',', $ids) . ').', 'error', 'action');
	}

}