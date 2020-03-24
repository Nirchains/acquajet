<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.mail.mail');

class FoxMailJoomlaMail extends FoxMailMail
{
	private $mail = null;
	
	public function __construct($mail = null)
	{
		$this->mail = is_null($mail) ? JFactory::getMailer() : $mail;
	}
	
	
	protected function _setFrom($email, $name)
	{
		$this->mail->setSender(array($email, $name));
		$this->mail->clearReplyTos();
	}
	
	
	protected function _setReplyTo($email, $name)
	{
		$this->mail->clearReplyTos();
		$this->mail->addReplyTo($email, $name);
	}
	
	
	protected function _addRecipient($email, $name = '')
	{
		$this->mail->addRecipient($email, $name);
	}
	
	
	protected function _addCC($email, $name = '')
	{
		$this->mail->addCc($email, $name);
	}
	
	
	protected function _addBCC($email, $name = '')
	{
		$this->mail->addBcc($email, $name);
	}
	
	
	protected function _setSubject($subject)
	{
		$this->mail->setSubject($subject);
	}
	
	
	protected function _setBodyParts($html, $text)
	{
		$this->mail->Encoding = 'quoted-printable';
		$this->mail->setBody($html);
		$this->mail->isHtml(true);
		$this->mail->AltBody = $text;
	}
	
	
	protected function _addAttachment($path, $name)
	{
		$this->mail->addAttachment($path, $name);
	}
	
	
	public function addDataAttachment($data, $name)
	{
		$this->mail->addStringAttachment($data, $name);
	}
	
	
	protected function _send()
	{
		$result = $this->mail->Send();
		if ($result !== true)
		{
			if (is_object($result))
			{
				return $result->getMessage();
			}
			
			if (!empty($this->mail->ErrorInfo))
			{
				return $this->mail->ErrorInfo;
			}
			
			return JText::_('JLIB_MAIL_FUNCTION_OFFLINE');
		}
		
		return true;
	}

}