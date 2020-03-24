<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
interface Swift_Transport_EsmtpHandler
{
	
	public function getHandledKeyword();
	
	public function setKeywordParams(array $parameters);
	
	public function afterEhlo(Swift_Transport_SmtpAgent $agent);
	
	public function getMailParams();
	
	public function getRcptParams();
	
	public function onCommand(Swift_Transport_SmtpAgent $agent, $command, $codes = array(), &$failedRecipients = null, &$stop = false);
	
	public function getPriorityOver($esmtpKeyword);
	
	public function exposeMixinMethods();
	
	public function resetState();
}