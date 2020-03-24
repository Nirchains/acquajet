<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.action.email');

class FoxActionEmailUser extends FoxActionEmail
{
	protected $type = 'User confirmation email';
	
	protected function isEnable()
	{
		$enabled = $this->params->get('user_notification', true);
		$email = $this->form->getDesign()->getFoxDesignItemByType('email');
		$valid = $email instanceof FoxDesignItem && $email->hasValue();
		return $enabled && $valid;
	}
	
	
	protected function prepare($mail)
	{
		$render = $this->form->getMessageRender();
		$config = JComponentHelper::getParams('com_foxcontact');
		$mail->setFrom($config->get('submitter_email_from', ''), $config->get('submitter_email_name', ''));
		$mail->addRecipient($this->form->getEmail(), $this->form->getName());
		$mail->setSubject($render->renderSubject('email_copy_subject'));
		$mail->setHtml($render->renderBody('email_copy_body'));
	}

}