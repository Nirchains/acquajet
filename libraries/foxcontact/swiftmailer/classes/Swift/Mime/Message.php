<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
interface Swift_Mime_Message extends Swift_Mime_MimeEntity
{
	
	public function generateId();
	
	public function setSubject($subject);
	
	public function getSubject();
	
	public function setDate($date);
	
	public function getDate();
	
	public function setReturnPath($address);
	
	public function getReturnPath();
	
	public function setSender($address, $name = null);
	
	public function getSender();
	
	public function setFrom($addresses, $name = null);
	
	public function getFrom();
	
	public function setReplyTo($addresses, $name = null);
	
	public function getReplyTo();
	
	public function setTo($addresses, $name = null);
	
	public function getTo();
	
	public function setCc($addresses, $name = null);
	
	public function getCc();
	
	public function setBcc($addresses, $name = null);
	
	public function getBcc();
}