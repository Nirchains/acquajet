<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.design.item_options');

class FoxDesignItemRadio extends FoxDesignItemOptions
{
	
	public function getLabelForId()
	{
		return '';
	}
	
	
	public function getLabelValuesClasses($form)
	{
		$render_type = $form->getDesign()->get('option.form.render') === 'inline' ? 'inline' : $this->get('render');
		return "fox-item-radio-label-{$render_type}";
	}
	
	
	protected function getDefaultValue()
	{
		return JFactory::getApplication()->input->get->get($this->get('unique_id'), null, 'string');
	}

}