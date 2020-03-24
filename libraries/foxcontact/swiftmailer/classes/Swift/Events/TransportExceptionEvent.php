<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Events_TransportExceptionEvent extends Swift_Events_EventObject
{
	private $_exception;
	
	public function __construct(Swift_Transport $transport, Swift_TransportException $ex)
	{
		parent::__construct($transport);
		$this->_exception = $ex;
	}
	
	
	public function getException()
	{
		return $this->_exception;
	}

}