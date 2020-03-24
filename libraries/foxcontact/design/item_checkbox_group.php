<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.design.item_options');

class FoxDesignItemCheckboxGroup extends FoxDesignItemOptions
{
	
	public function getLabelForId()
	{
		return '';
	}
	
	
	protected function hasSingleValue()
	{
		return false;
	}
	
	
	public function update(array $post_data)
	{
		$unique_id = $this->get('unique_id');
		$this->setValue($this->sanitize(isset($post_data[$unique_id]) ? $post_data[$unique_id] : array()));
	}
	
	
	public function isChecked($text)
	{
		$selected = $this->getValue();
		return is_array($selected) && in_array($text, $selected);
	}
	
	
	private function sanitize($texts)
	{
		$result = array();
		$valid_texts = array();
		foreach ($this->get('options') as $option)
		{
			$valid_texts[$option->get('text')] = true;
		}
		
		foreach (is_array($texts) ? $texts : array() as $text)
		{
			if (!in_array($text, $result) && isset($valid_texts[$text]))
			{
				$result[] = $text;
			}
		
		}
		
		return $result;
	}
	
	
	public function getLabelValuesClasses($form)
	{
		$render_type = $form->getDesign()->get('option.form.render') === 'inline' ? 'inline' : $this->get('render');
		return "fox-item-checkbox-group-label-{$render_type}";
	}
	
	
	public function getValueForUser()
	{
		return $this->getValueAsText();
	}
	
	
	public function getValueForAdmin()
	{
		return $this->getValueAsText();
	}
	
	
	public function getValueAsText()
	{
		return implode(', ', $this->getValue());
	}

}