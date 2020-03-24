<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.html.elem');

abstract class FoxHtmlResource
{
	private $js = array();
	private $css = array();
	
	public static function NewInstance()
	{
		$config = JComponentHelper::getParams('com_foxcontact');
		$class = "FoxHtmlResource{$config->get('resources_loading', 'Performance')}";
		return new $class();
	}
	
	
	public function Add($path, $type)
	{
		if (is_dir(JPATH_ROOT . $path))
		{
			$files = new GlobIterator(JPATH_ROOT . $path . "/*.{$type}");
			foreach ($files as $file)
			{
				$this->{$type}[] = $this->get_versioned_suffix($path . '/' . $file->getFilename());
			}
		
		}
		else
		{
			if (is_file(JPATH_ROOT . "{$path}.{$type}"))
			{
				$this->{$type}[] = $this->get_versioned_suffix("{$path}.{$type}");
			}
			else
			{
				$this->{$type}[] = $this->get_versioned_suffix("{$path}.min.{$type}");
			}
		
		}
		
		return $this;
	}
	
	
	public function ToDocument(JDocument $document)
	{
		foreach ($this->js as $js)
		{
			$this->addJs($document, $js);
		}
		
		foreach ($this->css as $css)
		{
			$this->addCss($document, $css);
		}
	
	}
	
	
	public function ToJSON()
	{
		return json_encode(get_object_vars($this), JSON_HEX_QUOT | JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP);
	}
	
	
	protected abstract function addCss(JDocument $document, $url);
	
	protected abstract function addJs(JDocument $document, $url);
	
	private function get_versioned_suffix($file)
	{
		$time = @filemtime(JPATH_ROOT . $file) or $time = 0;
		$suffix = JDEBUG ? '' : "?v={$time}";
		return JUri::root(true) . $file . $suffix;
	}

}


class FoxHtmlResourcePerformance extends FoxHtmlResource
{
	
	protected function addCss(JDocument $document, $url)
	{
		$document->addStyleSheet(htmlspecialchars($url));
	}
	
	
	protected function addJs(JDocument $document, $url)
	{
		$document->addScript(htmlspecialchars($url));
	}

}


class FoxHtmlResourceCompatibility extends FoxHtmlResource
{
	
	protected function addCss(JDocument $document, $url)
	{
		$document->addCustomTag(FoxHtmlElem::create('link')->attr('rel', 'stylesheet')->attr('href', $url)->attr('type', 'text/css')->render());
	}
	
	
	protected function addJs(JDocument $document, $url)
	{
		$document->addCustomTag(FoxHtmlElem::create('script')->attr('src', $url)->attr('type', 'text/javascript')->render());
	}

}