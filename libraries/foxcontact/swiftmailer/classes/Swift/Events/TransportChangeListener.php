<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
interface Swift_Events_TransportChangeListener extends Swift_Events_EventListener
{
	
	public function beforeTransportStarted(Swift_Events_TransportChangeEvent $evt);
	
	public function transportStarted(Swift_Events_TransportChangeEvent $evt);
	
	public function beforeTransportStopped(Swift_Events_TransportChangeEvent $evt);
	
	public function transportStopped(Swift_Events_TransportChangeEvent $evt);
}