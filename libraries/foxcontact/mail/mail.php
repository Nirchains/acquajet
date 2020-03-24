<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.roundcube.html2text');

abstract class FoxMailMail
{
	
	public static function getInstance()
	{
		$type = JComponentHelper::getParams('com_foxcontact')->get('mail_sender_type', 'Swift');
		jimport(strtolower("foxcontact.mail.{$type}"));
		$class_name = "FoxMail{$type}Mail";
		return new $class_name();
	}
	
	
	public function setFrom($email = '', $name = '')
	{
		$email = trim($email);
		$name = trim($name);
		$email = !empty($email) ? $email : trim(JFactory::getConfig()->get('mailfrom', ''));
		$name = !empty($name) ? $name : trim(JFactory::getConfig()->get('fromname', ''));
		if (!empty($email))
		{
			$this->_setFrom(JMailHelper::cleanAddress($email), $name);
		}
	
	}
	
	
	protected abstract function _setFrom($email, $name);
	
	public function setReplyTo($email, $name = '')
	{
		$email = trim($email);
		$name = trim($name);
		if (!empty($email))
		{
			$this->_setReplyTo(JMailHelper::cleanAddress($email), $name);
		}
	
	}
	
	
	protected abstract function _setReplyTo($email, $name);
	
	public function addRecipient($email, $name = '')
	{
		$email = trim($email);
		$name = trim($name);
		if (!empty($email))
		{
			$this->_addRecipient(JMailHelper::cleanAddress($email), $name);
		}
	
	}
	
	
	protected abstract function _addRecipient($email, $name);
	
	public function addCC($email, $name = '')
	{
		$email = trim($email);
		$name = trim($name);
		if (!empty($email))
		{
			$this->_addCC(JMailHelper::cleanAddress($email), $name);
		}
	
	}
	
	
	protected abstract function _addCC($email, $name);
	
	public function addBCC($email, $name = '')
	{
		$email = trim($email);
		$name = trim($name);
		if (!empty($email))
		{
			$this->_addBCC(JMailHelper::cleanAddress($email), $name);
		}
	
	}
	
	
	protected abstract function _addBCC($email, $name);
	
	public function setSubject($subject)
	{
		$this->_setSubject(trim($subject));
	}
	
	
	protected abstract function _setSubject($subject);
	
	public function setHtml($html)
	{
		$html2text = new Roundcube\html2text($html);
		$this->_setBodyParts($html, $html2text->get_text());
	}
	
	
	protected abstract function _setBodyParts($html, $text);
	
	public function addAttachment($path, $name)
	{
		if (is_file($path))
		{
			$this->_addAttachment($path, trim($name));
		}
	
	}
	
	
	protected abstract function _addAttachment($path, $name);
	
	public abstract function addDataAttachment($data, $name);
	
	public function send()
	{
		try
		{
			return JFactory::getConfig()->get('mailonline', '1') !== '1' ? JText::_('JLIB_MAIL_FUNCTION_OFFLINE') : $this->_send();
		}
		catch (Exception $e)
		{
			return $e->getMessage();
		}
	
	}
	
	
	protected abstract function _send();
}