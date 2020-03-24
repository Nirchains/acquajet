<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.html.resource');
jimport('foxcontact.html.util');

class FoxJoomlaEditor
{
	
	public static function init()
	{
		static $result = false;
		static $document = null;
		if (!$document)
		{
			$document = JFactory::getDocument();
			if ($document instanceof JDocumentHtml)
			{
				$editor = JFactory::getConfig()->get('editor', 'none');
				$supported = array('jce', 'codemirror', 'none');
				in_array($editor, $supported) or $editor = 'default';
				$class = "FoxJoomlaEditor{$editor}";
				new $class();
				FoxHtmlResource::NewInstance()->Add("/administrator/components/com_foxcontact/js/editors/{$editor}", 'js')->ToDocument($document);
				$result = true;
			}
		
		}
		
		return $result;
	}

}


class FoxJoomlaEditorDefault
{
	
	public function __construct()
	{
		JHtml::_('jquery.framework');
		JHtml::script('media/editors/tinymce/tinymce.min.js', false, false, false, false, false);
		JFactory::getLanguage()->load('plg_editors_tinymce', JPATH_ADMINISTRATOR, null, true);
		JText::script('PLG_TINY_ERR_UNSUPPORTEDBROWSER');
		$options = array('document_base_url' => JUri::root());
		$long_tag = JFactory::getLanguage()->getTag();
		$short_tag = substr($long_tag, 0, strpos($long_tag, '-'));
		foreach (array($short_tag, $long_tag) as $tag)
		{
			if (file_exists(JPATH_ROOT . '/media/editors/tinymce/langs/' . $tag . '.js'))
			{
				$options['language'] = $tag;
				break;
			}
		
		}
		
		FoxHtmlUtil::addMetaTagData('tinymce:options', $options);
		FoxHtmlUtil::addMetaTagData('tinymce:jdragdrop:options', array('setCustomDir' => JUri::root(true), 'uploadUri' => JUri::base() . 'index.php?option=com_media&task=file.upload&tmpl=component&' . JFactory::getSession()->getName() . '=' . JFactory::getSession()->getId() . '&' . JSession::getFormToken() . '=1' . '&asset=image&format=json'));
	}

}


class FoxJoomlaEditorJCE
{
	
	public function __construct()
	{
		$instance = JEditor::getInstance('jce');
		require_once JPATH_ROOT . '/components/com_jce/editor/libraries/classes/editor.php';
		if (!function_exists('wfimport'))
		{
			require JPATH_ADMINISTRATOR . '/components/com_jce/includes/base.php';
		}
		
		if (!defined('WF_VERSION'))
		{
			JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_FOXCONTACT_EDITOR_OBSOLETE', strtoupper(JFactory::getConfig()->get('editor', 'none'))), 'warning');
			return;
		}
		
		JFactory::getApplication()->registerEvent('onBeforeWfEditorRender', function (array &$settings)
		{
			unset($settings['content_css']);
			$settings['relative_urls'] = false;
		});
		$instance->display('fake', 'fake', 1920, 1080, 80, 25, false);
	}

}


class FoxJoomlaEditorCodeMirror
{
	
	public function __construct()
	{
		$document = JFactory::getDocument();
		$app = JFactory::getApplication();
		$app->registerEvent('onCodeMirrorBeforeDisplay', function ()
		{
			JFactory::$document = new JDocument();
		});
		$app->registerEvent('onCodeMirrorAfterDisplay', function ($data) use($document)
		{
			JFactory::$document = $document;
			FoxHtmlUtil::addMetaTagData('fox:codemirror:options', (array) $data->options);
		});
		$instance = JEditor::getInstance('codemirror');
		$instance->display('fake', 'fake', 1920, 1080, 80, 25, false);
	}

}


class FoxJoomlaEditorNone
{
}