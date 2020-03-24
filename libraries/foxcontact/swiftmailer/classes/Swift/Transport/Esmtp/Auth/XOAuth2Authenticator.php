<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Transport_Esmtp_Auth_XOAuth2Authenticator implements Swift_Transport_Esmtp_Authenticator
{
	
	public function getAuthKeyword()
	{
		return 'XOAUTH2';
	}
	
	
	public function authenticate(Swift_Transport_SmtpAgent $agent, $email, $token)
	{
		try
		{
			$param = $this->constructXOAuth2Params($email, $token);
			$agent->executeCommand('AUTH XOAUTH2 ' . $param . "\r\n", array(235));
			return true;
		}
		catch (Swift_TransportException $e)
		{
			$agent->executeCommand("RSET\r\n", array(250));
			return false;
		}
	
	}
	
	
	protected function constructXOAuth2Params($email, $token)
	{
		return base64_encode("user={$email}auth=Bearer {$token}");
	}

}