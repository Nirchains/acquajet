<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.system.log');
use FoxContact\Log;

class FoxDesignItemAntispam extends FoxDesignItem
{
	public $ProvidesData = false;
	
	public function __construct($value = array())
	{
		parent::__construct(array_merge_recursive($value, array('type' => 'antispam', 'unique_id' => 'antispam')));
	}
	
	
	public function addResources(JDocument $document)
	{
		FoxHtmlResource::NewInstance()->Add('/media/com_foxcontact/js/base', 'js')->ToDocument(JFactory::getDocument());
	}
	
	
	protected function getDefaultValue()
	{
		return array('honeypot' => '', 'interaction' => '0');
	}
	
	
	public function getHoneypotInputValue()
	{
		$values = $this->getValue();
		return (string) $values['honeypot'];
	}
	
	
	public function getInteractionInputValue()
	{
		$values = $this->getValue();
		return (string) $values['interaction'];
	}
	
	
	public function update(array $post_data)
	{
		$this->setValue(array('honeypot' => $post_data['message'], 'interaction' => $post_data['action']));
	}
	
	
	protected function check($value, array &$messages)
	{
		$form = FoxFormModel::getFormByUid($this->get('uid'));
		$disabled = !(bool) $form->getParams()->get('spam_check', 1);
		if ($disabled)
		{
			return;
		}
		
		$content = $this->check_content();
		$honeypot = $this->check_honeypot($value['honeypot']);
		$interaction = $this->check_interaction($value['interaction']);
		$success = $content && $honeypot && $interaction;
		if (!$success)
		{
			$messages[] = $this->getMessage(JText::_('COM_FOXCONTACT_ERR_SPAM_CONTENT_DETECTED'));
		}
	
	}
	
	
	protected function check_content()
	{
		$form = FoxFormModel::getFormByUid($this->get('uid'));
		$spam_words = $this->getSpamWords($form);
		if (count($spam_words) !== 0)
		{
			$text = $this->getTextToCheck($form);
			foreach ($spam_words as $word)
			{
				if (stripos($text, $word) !== false)
				{
					$this->log($form, 'content analyzer');
					return false;
				}
			
			}
		
		}
		
		return true;
	}
	
	
	protected function check_honeypot($value)
	{
		$empty = $this->isValueEmpty($value);
		if (!$empty)
		{
			$form = FoxFormModel::getFormByUid($this->get('uid'));
			$this->log($form, 'honeypot');
		}
		
		return $empty;
	}
	
	
	protected function check_interaction($value)
	{
		$empty = $this->isValueEmpty($value);
		$valid = intval($value) < 0;
		$result = !$empty && $valid;
		if (!$result)
		{
			$form = FoxFormModel::getFormByUid($this->get('uid'));
			$this->log($form, 'interaction');
		}
		
		return $result;
	}
	
	
	private function getSpamWords($form)
	{
		$spam_words = str_replace(array("\r", "\n"), '', $form->getParams()->get('spam_words', ''));
		if (empty($spam_words))
		{
			return array();
		}
		
		return explode(',', $spam_words);
	}
	
	
	private function getTextToCheck($form)
	{
		$text = '';
		foreach ($form->getDesign()->getItems() as $item)
		{
			if ($item->getType() === 'text_area')
			{
				$text .= " {$item->getValue()}";
			}
		
		}
		
		return $text;
	}
	
	
	protected function log($form, $module)
	{
		if ((bool) $form->getParams()->get('spam_log', true))
		{
			Log::GetInstance()->Add("Spam attempt blocked by module {$module}. " . json_encode($form->getData()), 'debug', 'spam');
		}
	
	}

}