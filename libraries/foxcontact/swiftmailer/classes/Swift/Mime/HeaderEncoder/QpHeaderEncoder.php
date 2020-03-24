<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Mime_HeaderEncoder_QpHeaderEncoder extends Swift_Encoder_QpEncoder implements Swift_Mime_HeaderEncoder
{
	
	public function __construct(Swift_CharacterStream $charStream)
	{
		parent::__construct($charStream);
	}
	
	
	protected function initSafeMap()
	{
		foreach (array_merge(range(97, 122), range(65, 90), range(48, 57), array(32, 33, 42, 43, 45, 47)) as $byte)
		{
			$this->_safeMap[$byte] = chr($byte);
		}
	
	}
	
	
	public function getName()
	{
		return 'Q';
	}
	
	
	public function encodeString($string, $firstLineOffset = 0, $maxLineLength = 0)
	{
		return str_replace(array(' ', '=20', "=\r\n"), array('_', '_', "\r\n"), parent::encodeString($string, $firstLineOffset, $maxLineLength));
	}

}