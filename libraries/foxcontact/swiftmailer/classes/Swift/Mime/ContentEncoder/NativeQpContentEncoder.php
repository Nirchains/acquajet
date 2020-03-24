<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Mime_ContentEncoder_NativeQpContentEncoder implements Swift_Mime_ContentEncoder
{
	private $charset;
	
	public function __construct($charset = null)
	{
		$this->charset = $charset ? $charset : 'utf-8';
	}
	
	
	public function charsetChanged($charset)
	{
		$this->charset = $charset;
	}
	
	
	public function encodeByteStream(Swift_OutputByteStream $os, Swift_InputByteStream $is, $firstLineOffset = 0, $maxLineLength = 0)
	{
		if ($this->charset !== 'utf-8')
		{
			throw new RuntimeException(sprintf('Charset "%s" not supported. NativeQpContentEncoder only supports "utf-8"', $this->charset));
		}
		
		$string = '';
		while (false !== ($bytes = $os->read(8192)))
		{
			$string .= $bytes;
		}
		
		$is->write($this->encodeString($string));
	}
	
	
	public function getName()
	{
		return 'quoted-printable';
	}
	
	
	public function encodeString($string, $firstLineOffset = 0, $maxLineLength = 0)
	{
		if ($this->charset !== 'utf-8')
		{
			throw new RuntimeException(sprintf('Charset "%s" not supported. NativeQpContentEncoder only supports "utf-8"', $this->charset));
		}
		
		return $this->_standardize(quoted_printable_encode($string));
	}
	
	
	protected function _standardize($string)
	{
		$string = preg_replace('~=0D(?!=0A)|(?<!=0D)=0A~', '=0D=0A', $string);
		$string = str_replace(array("\t=0D=0A", ' =0D=0A', '=0D=0A'), array("=09\r\n", "=20\r\n", "\r\n"), $string);
		switch ($end = ord(substr($string, -1)))
		{
			case 9:
				$string = substr_replace($string, '=09', -1);
				break;
			case 32:
				$string = substr_replace($string, '=20', -1);
				break;
		}
		
		return $string;
	}

}