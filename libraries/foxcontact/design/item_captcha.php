<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.html.captcha');
jimport('foxcontact.system.log');
use FoxContact\Log;

class FoxDesignItemCaptcha extends FoxDesignItem
{
	public $ProvidesData = false;
	
	protected function init()
	{
		$this->set('required', true);
	}
	
	
	public function getLabelForId()
	{
		return "{$this->getItemId()}-answer";
	}
	
	
	public function getState()
	{
		$board = FoxFormModel::getFormByUid($this->get('uid'))->getBoard();
		return !$board->isValidated() || $board->isFieldInvalid($this->get('unique_id')) ? 'not_valid' : 'valid';
	}
	
	
	public function getAnswer()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)->select($db->quoteName('answer'))->from($db->quoteName('#__foxcontact_captcha'))->where("{$db->quoteName('session_id')} = {$db->quote(JFactory::getSession()->getId())}")->where("{$db->quoteName('form_uid')} = {$db->quote($this->get('uid'))}");
		return (string) $db->setQuery($query)->loadResult();
	}
	
	
	public function setAnswer($answer)
	{
		self::deleteExpiredAnswers();
		$db = JFactory::getDbo();
		$record = (object) array('session_id' => JFactory::getSession()->getId(), 'form_uid' => $this->get('uid'), 'date' => time(), 'answer' => $answer);
		try
		{
			$db->insertObject('#__foxcontact_captcha', $record);
		}
		catch (Exception $e)
		{
			$db->updateObject('#__foxcontact_captcha', $record, array('session_id', 'form_uid'));
		}
	
	}
	
	
	private static function deleteExpiredAnswers()
	{
		$db = JFactory::getDbo();
		$limit = time() - JFactory::getConfig()->get('lifetime', 900) * 60;
		$query = $db->getQuery(true)->delete($db->quoteName('#__foxcontact_captcha'))->where("{$db->quoteName('date')} < {$db->quote($limit)}");
		return $db->setQuery($query)->execute();
	}
	
	
	protected function getDefaultValue()
	{
		return array();
	}
	
	
	protected function check($value, array &$messages)
	{
		$answer = $this->getAnswer();
		if (empty($answer) || empty($value) || self::faultTolerance($value) !== self::faultTolerance($answer))
		{
			$messages[] = $this->getMessage(JText::sprintf('COM_FOXCONTACT_ERR_INVALID_VALUE', $this->get('label')));
			Log::GetInstance()->Add("Built-in CAPTCHA validation failed. Expected '{$answer}', got '{$value}'.", 'debug', 'form');
		}
	
	}
	
	
	private static function faultTolerance($string)
	{
		$string = strtolower($string);
		$string = preg_replace('/[l1]/', 'i', $string);
		$string = preg_replace('/[0]/', 'o', $string);
		$string = preg_replace('/[q9]/', 'g', $string);
		$string = preg_replace('/[5]/', 's', $string);
		$string = preg_replace('/[8]/', 'b', $string);
		return $string;
	}
	
	
	public function onBeforeRender(FoxFormForm $form)
	{
		if (!extension_loaded('gd'))
		{
			$msg = JText::_('COM_FOXCONTACT_NO_GD_LIBRARY');
			$btn = '<a class="btn btn-info" href="http://www.fox.ra.it/forum/24-troubleshooting/2990-built-in-captcha-feature-is-enabled-but-it-doesn-t-work.html#NO_GD_LIBRARY">' . JText::_('COM_FOXCONTACT_READ_MORE') . '</a>';
			$form->getBoard()->add($msg . ' ' . $btn, FoxFormBoard::error, true);
		}
		else
		{
			if (!function_exists('imagettftext'))
			{
				$msg = JText::_('COM_FOXCONTACT_NO_FREETYPE_SUPPORT');
				$btn = '<a class="btn btn-info" href="http://www.fox.ra.it/forum/24-troubleshooting/2990-built-in-captcha-feature-is-enabled-but-it-doesn-t-work.html#NO_FREETYPE_SUPPORT">' . JText::_('COM_FOXCONTACT_READ_MORE') . '</a>';
				$form->getBoard()->add($msg . ' ' . $btn, FoxFormBoard::error, true);
			}
		
		}
	
	}

}