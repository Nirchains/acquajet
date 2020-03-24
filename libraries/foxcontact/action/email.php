<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.action.base');
jimport('foxcontact.html.encoder');
jimport('foxcontact.design.item_attachments');
jimport('foxcontact.mail.mail');
use FoxContact\Log;

abstract class FoxActionEmail extends FoxActionBase
{
	protected $type = 'Abstract notification email';
	
	public function process($target)
	{
		if ($this->isEnable())
		{
			$mail = FoxMailMail::getInstance();
			$this->prepare($mail);
			return $this->send($mail);
		}
		
		return true;
	}
	
	
	protected function isEnable()
	{
		return true;
	}
	
	
	protected abstract function prepare($mail);
	
	protected function addAttachments($mail)
	{
		$item = $this->form->getDesign()->getFoxDesignItemByType('attachments') or $item = new FoxDesignItemAttachments(array());
		$root = JPATH_SITE . '/components/com_foxcontact/uploads/';
		$sum = 0;
		foreach ($item->getValue() as $file)
		{
			$sum += filesize("{$root}{$file['filename']}");
		}
		
		$mailbox_size = constant($item->get('mail.size', 'MB20'));
		if ($sum < $mailbox_size)
		{
			foreach ($item->getValue() as $file)
			{
				$mail->addAttachment("{$root}{$file['filename']}", $file['realname']);
			}
		
		}
		else
		{
			$sum = $item->getHumanReadable($sum);
			$mailbox_size = $item->getHumanReadable($mailbox_size);
			Log::GetInstance()->Add("Total attachments size ({$sum}) exceeds the mailbox capacity configured for this form ({$mailbox_size}). Email attachments skipped.", 'info', 'action');
			$mail->addDataAttachment(JText::sprintf('COM_FOXCONTACT_WARN_ATTACHMENTS_SKIPPED', $sum, $mailbox_size), JText::_('COM_FOXCONTACT_ATTACHMENTS') . '.txt');
		}
	
	}
	
	
	private function send($mail)
	{
		$result = $mail->send();
		if ($result !== true)
		{
			$info = (string) $result;
			Log::GetInstance()->Add("{$this->type} Unable to send email. ({$info})", 'error', 'action');
			$info = FoxHtmlEncoder::encode($info);
			$this->form->getBoard()->add(JText::_('COM_FOXCONTACT_ERR_SENDING_MAIL') . ". {$info}", FoxFormBoard::error);
			return false;
		}
		
		Log::GetInstance()->Add("{$this->type} sent.", 'info', 'action');
		return true;
	}

}