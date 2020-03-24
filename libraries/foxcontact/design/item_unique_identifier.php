<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.form.sequencer');

class FoxDesignItemUniqueIdentifier extends FoxDesignItem
{
	
	public function update(array $post_data)
	{
	}
	
	
	public function validate(array &$messages)
	{
		return true;
	}
	
	
	protected function getDefaultValue()
	{
		return array();
	}
	
	
	public function onBeforeProcess()
	{
		$class = 'FoxSequencer' . $this->get('mode', 'N');
		$sequencer = new $class(trim($this->get('series')));
		$this->setValue($sequencer->getNextValue());
	}

}