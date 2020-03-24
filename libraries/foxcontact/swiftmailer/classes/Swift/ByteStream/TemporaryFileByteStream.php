<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_ByteStream_TemporaryFileByteStream extends Swift_ByteStream_FileByteStream
{
	
	public function __construct()
	{
		$filePath = tempnam(sys_get_temp_dir(), 'FileByteStream');
		if ($filePath === false)
		{
			throw new Swift_IoException('Failed to retrieve temporary file name.');
		}
		
		parent::__construct($filePath, true);
	}
	
	
	public function getContent()
	{
		if (($content = file_get_contents($this->getPath())) === false)
		{
			throw new Swift_IoException('Failed to get temporary file content.');
		}
		
		return $content;
	}
	
	
	public function __destruct()
	{
		if (file_exists($this->getPath()))
		{
			@unlink($this->getPath());
		}
	
	}

}