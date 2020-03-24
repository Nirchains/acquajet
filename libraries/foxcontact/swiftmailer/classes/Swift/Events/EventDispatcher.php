<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
interface Swift_Events_EventDispatcher
{
	
	public function createSendEvent(Swift_Transport $source, Swift_Mime_Message $message);
	
	public function createCommandEvent(Swift_Transport $source, $command, $successCodes = array());
	
	public function createResponseEvent(Swift_Transport $source, $response, $valid);
	
	public function createTransportChangeEvent(Swift_Transport $source);
	
	public function createTransportExceptionEvent(Swift_Transport $source, Swift_TransportException $ex);
	
	public function bindEventListener(Swift_Events_EventListener $listener);
	
	public function dispatchEvent(Swift_Events_EventObject $evt, $target);
}