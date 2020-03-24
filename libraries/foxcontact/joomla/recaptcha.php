<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class FoxJoomlaRecaptcha extends JCaptcha
{
	protected $captcha = null;
	protected $params = null;
	protected $last_exec_has_error = false;
	
	public function __construct($captcha)
	{
		$this->captcha = $captcha;
		$plugin = JPluginHelper::getPlugin('captcha', 'recaptcha');
		$this->params = is_object($plugin) ? $plugin->params : null;
	}
	
	
	public static function getInstance($namespace = 'fox', array $options = array())
	{
		return self::exec(function () use($namespace)
		{
			try
			{
				$captcha = JCaptcha::getInstance('recaptcha', array('namespace' => $namespace));
			}
			catch (RuntimeException $e)
			{
				$captcha = null;
			}
			
			return new FoxJoomlaRecaptcha($captcha);
		});
	}
	
	
	private static function exec($function, $instance = null)
	{
		$application = JFactory::getApplication();
		$messages0 = $application->getMessageQueue();
		$has_error = false;
		try
		{
			$result = !is_null($instance) ? $function($instance->captcha) : $function();
		}
		catch (RuntimeException $e)
		{
			$has_error = true;
			$result = false;
		}
		
		$messages1 = $application->getMessageQueue();
		$has_error |= count($messages1) > count($messages0);
		if (!is_null($instance))
		{
			$instance->last_exec_has_error = $has_error;
		}
		
		if ($has_error)
		{
			FoxJApplicationCms::writeMessageQueueOnApplication($application, $messages0);
		}
		
		return $result;
	}
	
	
	private static function exec40($function, $instance = null)
	{
		try
		{
			$result = !is_null($instance) ? $function($instance->captcha) : $function();
		}
		catch (RuntimeException $e)
		{
			$result = false;
		}
		
		return $result;
	}
	
	
	public function lastExecAsError()
	{
		return $this->last_exec_has_error;
	}
	
	
	public function isEnable(&$reason = '')
	{
		if (is_null($this->captcha) || is_null($this->params))
		{
			$reason = 'disabled';
			return false;
		}
		
		$version = trim($this->params->get('version', '1.0'));
		if (version_compare($version, '2.0', '<'))
		{
			$reason = 'version_less_2';
			return false;
		}
		
		$public_key = trim($this->params->get('public_key', ''));
		if (empty($public_key))
		{
			$reason = 'empty_public_key';
			return false;
		}
		
		$private_key = trim($this->params->get('private_key', ''));
		if (empty($private_key))
		{
			$reason = 'empty_private_key';
			return false;
		}
		
		return true;
	}
	
	
	public function initialise($id)
	{
		return self::exec(function ($captcha) use($id)
		{
			return $captcha->initialise($id);
		}, $this);
	}
	
	
	public function display($name, $id, $class = '')
	{
		return self::exec(function ($captcha) use($name, $id, $class)
		{
			return $captcha->display($name, $id, $class);
		}, $this);
	}
	
	
	public function checkAnswer($code)
	{
		return self::exec(function ($captcha) use($code)
		{
			return $captcha->checkAnswer($code);
		}, $this);
	}
	
	
	public function getState()
	{
		return self::exec(function ($captcha)
		{
			return $captcha->getState();
		}, $this);
	}
	
	
	public function attach($observer)
	{
		self::exec(function ($captcha) use($observer)
		{
			$captcha->attach($observer);
		}, $this);
	}
	
	
	public function detach($observer)
	{
		return self::exec(function ($captcha) use($observer)
		{
			return $captcha->detach($observer);
		}, $this);
	}

}


class FoxJApplicationCms extends JApplicationCms
{
	
	public static function writeMessageQueueOnApplication(JApplicationCms $application, array $queue)
	{
		$application->_messageQueue = $queue;
	}

}