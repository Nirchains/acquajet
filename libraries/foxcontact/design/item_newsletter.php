<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.form.newsletter');

class FoxDesignItemNewsletter extends FoxDesignItem
{
	public $ProvidesData = false;
	
	protected function hasSingleValue()
	{
		return !$this->get('display_checkbox') || $this->get('single_checkbox');
	}
	
	
	protected function getDefaultValue()
	{
		if ($this->get('preselected'))
		{
			return !$this->hasSingleValue() ? $this->getNewslettersIds() : JText::_('JYES');
		}
		
		return !$this->hasSingleValue() ? array() : null;
	}
	
	
	public function getLabelForId()
	{
		return $this->get('display_checkbox') && $this->get('single_checkbox') ? $this->getItemId() : '';
	}
	
	
	public function getRenderMode(FoxFormForm $form)
	{
		if (!$this->get('display_checkbox'))
		{
			return 'auto';
		}
		
		if ($this->get('single_checkbox'))
		{
			switch ($this->get('label_position_checkbox'))
			{
				case 'after':
					return 'single_after';
				case 'before':
					if ($form->getDesign()->get('option.label.position') === 'inside')
					{
						return $form->getDesign()->get('option.form.render') === 'inline' ? 'single_before_force_label' : 'single_after';
					}
					
					return 'single_before';
				default:
					return 'single_after';
			}
		
		}
		
		return 'multiple';
	}
	
	
	public function update(array $post_data)
	{
		$unique_id = $this->get('unique_id');
		if ($this->hasSingleValue())
		{
			$this->setValue(isset($post_data[$unique_id]) ? JText::_('JYES') : JText::_('JNO'));
		}
		else
		{
			$this->setValue($this->sanitize(isset($post_data[$unique_id]) ? $post_data[$unique_id] : array()));
		}
	
	}
	
	
	private function sanitize($ids)
	{
		$result = array();
		$valid_ids = $this->getNewslettersIds();
		foreach (is_array($ids) ? $ids : array() as $id)
		{
			if (in_array($id, $valid_ids) && !in_array($id, $result))
			{
				$result[] = $id;
			}
		
		}
		
		return $result;
	}
	
	
	private function subscribeToAll()
	{
		if (!$this->get('display_checkbox'))
		{
			return true;
		}
		
		if ($this->get('single_checkbox'))
		{
			return $this->getValue() === JText::_('JYES');
		}
		
		return false;
	}
	
	
	public function isChecked($id)
	{
		$selected = $this->getValue();
		return is_array($selected) && in_array($id, $selected);
	}
	
	
	public function getNewsletterType()
	{
		return $this->get('newsletter.type');
	}
	
	
	private function getNewslettersIds()
	{
		$ids = array();
		$newsletters = $this->getNewsletters();
		if (!is_null($newsletters))
		{
			foreach ($newsletters as $option)
			{
				$ids[] = $option['value'];
			}
		
		}
		
		return $ids;
	}
	
	
	public function getSelectedIds()
	{
		$ids = $this->getNewslettersIds();
		if (empty($ids))
		{
			return array();
		}
		
		$selected = !$this->subscribeToAll() ? $this->getValue() : $ids;
		if (!is_array($selected))
		{
			return array();
		}
		
		return array_values(array_intersect($ids, $selected));
	}
	
	
	public function getNewsletters()
	{
		$newsletter = FoxFormNewsletter::load($this->getNewsletterType(), (array) $this->get('newsletter.list'));
		return !is_null($newsletter) ? $newsletter['options'] : null;
	}

}