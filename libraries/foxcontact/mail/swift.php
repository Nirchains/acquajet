<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.swiftmailer.swift_required');

class FoxMailSwiftMail extends FoxMailMail
{
	private static $def_mailer_from_joomla_config = null;
	private $mailer = null, $message = null;
	
	public function __construct($mailer = null)
	{
		$this->mailer = is_null($mailer) ? self::getDefMailerFromJoomlaConfig() : $mailer;
		$this->message = Swift_Message::newInstance();
	}
	
	
	private static function getDefMailerFromJoomlaConfig()
	{
		if (is_null(self::$def_mailer_from_joomla_config))
		{
			$config = JFactory::getConfig();
			switch (strtolower($config->get('mailer', 'mail')))
			{
				case 'mail':
					$transport = Swift_MailTransport::newInstance();
					break;
				case 'sendmail':
					$transport = Swift_SendmailTransport::newInstance();
					$command = $config->get('sendmail', '') . ' -t -i';
					if (!empty($command))
					{
						$transport->setCommand($command);
					}
					
					break;
				case 'smtp':
					$transport = Swift_SmtpTransport::newInstance();
					$transport->setHost($config->get('smtphost', 'localhost'));
					$transport->setPort((int) $config->get('smtpport', 25));
					if ($config->get('smtpsecure', 'none') != 'none')
					{
						$transport->setEncryption($config->get('smtpsecure'));
					}
					
					if ($config->get('smtpauth', '0') === '1')
					{
						$transport->setUsername($config->get('smtpuser'));
						$transport->setPassword($config->get('smtppass'));
					}
					
					break;
				default:
					$transport = Swift_MailTransport::newInstance();
					break;
			}
			
			self::$def_mailer_from_joomla_config = Swift_Mailer::newInstance($transport);
		}
		
		return self::$def_mailer_from_joomla_config;
	}
	
	
	protected function _setFrom($email, $name)
	{
		$this->message->setFrom($email, $name);
	}
	
	
	protected function _setReplyTo($email, $name)
	{
		$this->message->setReplyTo($email, $name);
	}
	
	
	protected function _addRecipient($email, $name)
	{
		$this->message->addTo($email, $name);
	}
	
	
	protected function _addCC($email, $name)
	{
		$this->message->addCc($email, $name);
	}
	
	
	protected function _addBCC($email, $name)
	{
		$this->message->addBcc($email, $name);
	}
	
	
	protected function _setSubject($subject)
	{
		$this->message->setSubject($subject);
	}
	
	
	protected function _setBodyParts($html, $text)
	{
		$this->message->setBody($html, 'text/html');
		$this->message->addPart($text, 'text/plain');
	}
	
	
	protected function _addAttachment($path, $name)
	{
		$this->message->attach(Swift_Attachment::fromPath($path)->setFilename($name));
	}
	
	
	public function addDataAttachment($data, $name)
	{
		$this->message->attach(new Swift_Attachment($data, $name));
	}
	
	
	protected function _send()
	{
		$this->mailer->send($this->message, $fail_emails);
		return count($fail_emails) !== 0 ? 'Fail to deliver to: ' . implode(', ', $fail_emails) : true;
	}

}