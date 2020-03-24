<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.action.base');
jimport('foxcontact.form.newsletter');
use FoxContact\Log;

class FoxActionNewsletter extends FoxActionBase
{
	
	public function process($target)
	{
		$name = $this->form->getName();
		$email = $this->form->getEmail();
		$this->_notify_plugins($name, $email);
		$this->_notify_newsletters($name, $email);
		return true;
	}
	
	
	private function _notify_plugins($name, $email)
	{
		JPluginHelper::importPlugin('contact');
		$dispatcher = JEventDispatcher::getInstance();
		$data = FoxFormModel::getFormByUid($_REQUEST['uid'])->getData();
		$dispatcher->trigger('onSubmitFoxContact', array($data));
		Log::GetInstance()->Add('Form data sent to listeners through Joomla plugin subsystem.', 'info', 'action');
	}
	
	
	private function _notify_newsletters($name, $email)
	{
		$item = $this->form->getDesign()->getFoxDesignItemByType('newsletter');
		if (!is_null($item))
		{
			FoxFormNewsletter::subscribe($item->getNewsletterType(), $item->getSelectedIds(), $name, $email);
		}
	
	}

}