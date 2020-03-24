<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.html.resource');

class FoxDesignItemTextarea extends FoxDesignItem
{
	
	public function autoResize()
	{
		return $this->get('elastic') ? 'elastic' : '';
	}
	
	
	public function countDown()
	{
		return $this->get('max_length') ? 'countdown' : '';
	}
	
	
	public function getOptions()
	{
		$options = array('max' => (int) $this->get('max_length'));
		return json_encode($options);
	}

}