<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Transport_Esmtp_Auth_CramMd5Authenticator implements Swift_Transport_Esmtp_Authenticator
{
	
	public function getAuthKeyword()
	{
		return 'CRAM-MD5';
	}
	
	
	public function authenticate(Swift_Transport_SmtpAgent $agent, $username, $password)
	{
		try
		{
			$challenge = $agent->executeCommand("AUTH CRAM-MD5\r\n", array(334));
			$challenge = base64_decode(substr($challenge, 4));
			$message = base64_encode($username . ' ' . $this->_getResponse($password, $challenge));
			$agent->executeCommand(sprintf("%s\r\n", $message), array(235));
			return true;
		}
		catch (Swift_TransportException $e)
		{
			$agent->executeCommand("RSET\r\n", array(250));
			return false;
		}
	
	}
	
	
	private function _getResponse($secret, $challenge)
	{
		if (strlen($secret) > 64)
		{
			$secret = pack('H32', md5($secret));
		}
		
		if (strlen($secret) < 64)
		{
			$secret = str_pad($secret, 64, chr(0));
		}
		
		$k_ipad = substr($secret, 0, 64) ^ str_repeat(chr(54), 64);
		$k_opad = substr($secret, 0, 64) ^ str_repeat(chr(92), 64);
		$inner = pack('H32', md5($k_ipad . $challenge));
		$digest = md5($k_opad . $inner);
		return $digest;
	}

}