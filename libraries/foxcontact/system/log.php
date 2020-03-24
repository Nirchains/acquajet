<?php /**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
namespace FoxContact;

defined('_JEXEC') or die(file_get_contents('index.html'));

class FileAdapter
{
	const HEADER = '<?php die() ?>';
	public $path = '';
	public $handle = null;
	
	public function __construct()
	{
		$config = \JFactory::getConfig();
		$this->path = $config->get('log_path') . '/foxcontact.php';
		$this->open();
	}
	
	
	public function __destruct()
	{
		@fclose($this->handle);
	}
	
	
	public function Reset()
	{
		@file_put_contents($this->path, self::HEADER . PHP_EOL);
	}
	
	
	public function Write($buffer)
	{
		@fwrite($this->handle, $buffer . PHP_EOL);
	}
	
	
	public function open()
	{
		if (!file_exists($this->path))
		{
			@file_put_contents($this->path, self::HEADER . PHP_EOL);
		}
		
		$this->handle = @fopen($this->path, 'a');
	}

}


class Log
{
	private $file;
	
	public static function GetInstance()
	{
		static $instance = null;
		if (!$instance)
		{
			$instance = new Log();
		}
		
		return $instance;
	}
	
	
	public function __construct()
	{
		$this->file = new FileAdapter();
	}
	
	
	public function Clean()
	{
		$this->file->Reset();
	}
	
	
	public function Add($message, $level, $origin)
	{
		$now = \JFactory::getDate()->format('Y-m-d H:i:s');
		$level = str_pad($level, 5);
		$origin = str_pad($origin, 6);
		$this->file->Write($now . "\t" . $level . "\t" . $origin . "\t" . $message);
	}

}