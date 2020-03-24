<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.design.base');

class FoxDesignItem extends FoxDesignBase
{
	public $ProvidesData = true;
	protected $data;
	
	public function setData(&$data)
	{
		$this->data =& $data;
	}
	
	
	protected function finalize()
	{
		$this->set('unique_id', strtolower($this->get('unique_id')));
	}
	
	
	public function onBeforeRender(FoxFormForm $form)
	{
	}
	
	
	public function addResources(JDocument $document)
	{
	}
	
	
	public function onAfterRender()
	{
	}
	
	
	public function getType()
	{
		return $this->get('type');
	}
	
	
	public function getItemId()
	{
		return "fox-{$this->get('uid')}-{$this->get('unique_id')}";
	}
	
	
	public function getLabelForId()
	{
		return $this->getItemId();
	}
	
	
	public function getBoxId()
	{
		return "{$this->getItemId()}-box";
	}
	
	
	public function getPlaceholder()
	{
		$placeholder = '';
		switch (FoxFormModel::getFormByUid($this->get('uid'))->getDesign()->get('option.label.position'))
		{
			case 'inside':
				$placeholder = !$this->isEmpty('label') ? $this->get('label') : $this->get('placeholder');
				if ($this->get('required'))
				{
					$placeholder .= '*';
				}
				
				break;
			case 'beside':
				$placeholder = $this->get('placeholder');
				break;
		}
		
		return $placeholder;
	}
	
	
	public function getInputName()
	{
		return $this->hasSingleValue() ? "fox_form[{$this->get('uid')}][{$this->get('unique_id')}]" : "fox_form[{$this->get('uid')}][{$this->get('unique_id')}][]";
	}
	
	
	protected function hasSingleValue()
	{
		return true;
	}
	
	
	public function getInputValue()
	{
		return (string) $this->getValue();
	}
	
	
	public function getValueForUser()
	{
		return (string) $this->getValue();
	}
	
	
	public function isValueForUserHtml()
	{
		return false;
	}
	
	
	public function getValueForAdmin()
	{
		return (string) $this->getValue();
	}
	
	
	public function isValueForAdminHtml()
	{
		return false;
	}
	
	
	public function getValue()
	{
		return isset($this->data[$this->get('unique_id')]) ? $this->data[$this->get('unique_id')] : $this->getDefaultValue();
	}
	
	
	public function getValueAsText()
	{
		return (string) $this->getValue();
	}
	
	
	public function hasValue()
	{
		return !$this->isValueEmpty($this->getValue());
	}
	
	
	protected function getDefaultValue()
	{
		$backend_default = $this->get('def_val', $this->hasSingleValue() ? null : array());
		$field_name = $this->get('unique_id');
		return JFactory::getApplication()->input->get->get($field_name, $backend_default, 'string');
	}
	
	
	public function setValue($value)
	{
		$this->data[$this->get('unique_id')] = $value;
	}
	
	
	public function update(array $post_data)
	{
		$this->setValue(isset($post_data[$this->get('unique_id')]) ? $post_data[$this->get('unique_id')] : '');
	}
	
	
	public function validate(array &$messages)
	{
		$current_count = count($messages);
		$this->check($this->getValue(), $messages);
		return $current_count === count($messages);
	}
	
	
	protected function check($value, array &$messages)
	{
		if ($this->get('required') && $this->isValueEmpty($value))
		{
			$messages[] = $this->getMessage(JText::sprintf('JLIB_FORM_VALIDATE_FIELD_REQUIRED', $this->get('label')));
		}
	
	}
	
	
	protected function isValueEmpty($value)
	{
		return is_null($value) || !is_array($value) && trim($value) === '' || is_array($value) && count($value) === 0;
	}
	
	
	protected function getMessage($msg, $lvl = FoxFormBoard::error)
	{
		return array('src' => $this->get('unique_id'), 'msg' => $msg, 'lvl' => $lvl);
	}
	
	
	public function getRecipients($key)
	{
		return array();
	}

}