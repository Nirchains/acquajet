<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Events_ResponseEvent extends Swift_Events_EventObject
{
	private $_valid;
	private $_response;
	
	public function __construct(Swift_Transport $source, $response, $valid = false)
	{
		parent::__construct($source);
		$this->_response = $response;
		$this->_valid = $valid;
	}
	
	
	public function getResponse()
	{
		return $this->_response;
	}
	
	
	public function isValid()
	{
		return $this->_valid;
	}

}