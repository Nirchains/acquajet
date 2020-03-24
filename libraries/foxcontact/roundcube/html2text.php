<?php /**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
namespace Roundcube;

defined('_JEXEC') or die;
if (!function_exists('mb_strtoupper'))
{
	function mb_strtoupper($s)
	{
		return strtoupper($s);
	}
}


class html2text
{
	protected $html;
	protected $text;
	protected $width = 70;
	protected $charset = 'UTF-8';
	protected $search = array('/\\r/', '/^.*<body[^>]*>\\n*/is', '/<head[^>]*>.*?<\\/head>/is', '/<script[^>]*>.*?<\\/script>/is', '/<style[^>]*>.*?<\\/style>/is', '/[\\n\\t]+/', '/<p[^>]*>/i', '/<\\/p>[\\s\\n\\t]*<div[^>]*>/i', '/<br[^>]*>[\\s\\n\\t]*<div[^>]*>/i', '/<br[^>]*>\\s*/i', '/<i[^>]*>(.*?)<\\/i>/i', '/<em[^>]*>(.*?)<\\/em>/i', '/(<ul[^>]*>|<\\/ul>)/i', '/(<ol[^>]*>|<\\/ol>)/i', '/<li[^>]*>(.*?)<\\/li>/i', '/<li[^>]*>/i', '/<hr[^>]*>/i', '/<div[^>]*>/i', '/(<table[^>]*>|<\\/table>)/i', '/(<tr[^>]*>|<\\/tr>)/i', '/<td[^>]*>(.*?)<\\/td>/i');
	protected $replace = array('', '', '', '', '', ' ', "\n\n", "\n<div>", '<div>', "\n", '_\\1_', '_\\1_', "\n\n", "\n\n", "\t* \\1\n", "\n\t* ", "\n-------------------------\n", "<div>\n", "\n\n", "\n", "\t\t\\1\n");
	protected $ent_search = array('/&(nbsp|#160);/i', '/&(quot|rdquo|ldquo|#8220|#8221|#147|#148);/i', '/&(apos|rsquo|lsquo|#8216|#8217);/i', '/&gt;/i', '/&lt;/i', '/&(copy|#169);/i', '/&(trade|#8482|#153);/i', '/&(reg|#174);/i', '/&(mdash|#151|#8212);/i', '/&(ndash|minus|#8211|#8722);/i', '/&(bull|#149|#8226);/i', '/&(pound|#163);/i', '/&(euro|#8364);/i', '/&(amp|#38);/i', '/[ ]{2,}/');
	protected $ent_replace = array(' ', '"', '\'', '>', '<', '(c)', '(tm)', '(R)', '--', '-', '*', '£', 'EUR', '|+|amp|+|', ' ');
	protected $callback_search = array('/<(a) [^>]*href=("|\')([^"\']+)\\2[^>]*>(.*?)<\\/a>/i', '/<(h)[123456]( [^>]*)?>(.*?)<\\/h[123456]>/i', '/<(b)( [^>]*)?>(.*?)<\\/b>/i', '/<(strong)( [^>]*)?>(.*?)<\\/strong>/i', '/<(th)( [^>]*)?>(.*?)<\\/th>/i');
	protected $pre_search = array("/\n/", "/\t/", '/ /', '/<pre[^>]*>/', '/<\\/pre>/');
	protected $pre_replace = array('<br>', '&nbsp;&nbsp;&nbsp;&nbsp;', '&nbsp;', '', '');
	protected $allowed_tags = '';
	protected $url;
	protected $_converted = false;
	protected $_link_list = array();
	protected $_do_links = true;
	
	public function __construct($source = '', $from_file = false, $do_links = true, $width = 75, $charset = 'UTF-8')
	{
		if (!empty($source))
		{
			$this->set_html($source, $from_file);
		}
		
		$this->set_base_url();
		$this->_do_links = $do_links;
		$this->width = $width;
		$this->charset = $charset;
	}
	
	
	public function set_html($source, $from_file = false)
	{
		if ($from_file && file_exists($source))
		{
			$this->html = file_get_contents($source);
		}
		else
		{
			$this->html = $source;
		}
		
		$this->_converted = false;
	}
	
	
	public function get_text()
	{
		if (!$this->_converted)
		{
			$this->_convert();
		}
		
		return $this->text;
	}
	
	
	public function print_text()
	{
		print $this->get_text();
	}
	
	
	public function set_allowed_tags($allowed_tags = '')
	{
		if (!empty($allowed_tags))
		{
			$this->allowed_tags = $allowed_tags;
		}
	
	}
	
	
	public function set_base_url($url = '')
	{
		if (empty($url))
		{
			if (!empty($_SERVER['HTTP_HOST']))
			{
				$this->url = 'http://' . $_SERVER['HTTP_HOST'];
			}
			else
			{
				$this->url = '';
			}
		
		}
		else
		{
			if (substr($url, -1) == '/')
			{
				$url = substr($url, 0, -1);
			}
			
			$this->url = $url;
		}
	
	}
	
	
	protected function _convert()
	{
		$this->_link_list = array();
		$text = $this->html;
		$this->_converter($text);
		if (!empty($this->_link_list))
		{
			$text .= "\n\nLinks:\n------\n";
			foreach ($this->_link_list as $idx => $url)
			{
				$text .= '[' . ($idx + 1) . '] ' . $url . "\n";
			}
		
		}
		
		$this->text = $text;
		$this->_converted = true;
	}
	
	
	protected function _converter(&$text)
	{
		$this->_convert_blockquotes($text);
		$this->_convert_pre($text);
		$text = preg_replace($this->search, $this->replace, $text);
		$text = preg_replace_callback($this->callback_search, array($this, 'tags_preg_callback'), $text);
		$text = strip_tags($text, $this->allowed_tags);
		$text = preg_replace($this->ent_search, $this->ent_replace, $text);
		$text = html_entity_decode($text, ENT_QUOTES, $this->charset);
		$text = preg_replace('/\\xC2\\xA0/', ' ', $text);
		$text = preg_replace('/&([a-zA-Z0-9]{2,6}|#[0-9]{2,4});/', '', $text);
		$text = str_replace('|+|amp|+|', '&', $text);
		$text = preg_replace("/\n\\s+\n/", "\n\n", $text);
		$text = preg_replace("/[\n]{3,}/", "\n\n", $text);
		$text = ltrim($text, "\n");
		if ($this->width > 0)
		{
			$text = wordwrap($text, $this->width);
		}
	
	}
	
	
	protected function _build_link_list($link, $display)
	{
		if (!$this->_do_links || empty($link))
		{
			return $display;
		}
		
		if (preg_match('!^(javascript:|mailto:|#)!i', $link))
		{
			return $display;
		}
		
		if ($link === $display)
		{
			return $display;
		}
		
		if (preg_match('!^([a-z][a-z0-9.+-]+:)!i', $link))
		{
			$url = $link;
		}
		else
		{
			$url = $this->url;
			if (substr($link, 0, 1) != '/')
			{
				$url .= '/';
			}
			
			$url .= "{$link}";
		}
		
		if (($index = array_search($url, $this->_link_list)) === false)
		{
			$index = count($this->_link_list);
			$this->_link_list[] = $url;
		}
		
		return $display . ' [' . ($index + 1) . ']';
	}
	
	
	protected function _convert_pre(&$text)
	{
		while (preg_match('/<pre[^>]*>(.*)<\\/pre>/ismU', $text, $matches))
		{
			$this->pre_content = $matches[1];
			$this->pre_content = preg_replace_callback($this->callback_search, array($this, 'tags_preg_callback'), $this->pre_content);
			$this->pre_content = sprintf('<div><br>%s<br></div>', preg_replace($this->pre_search, $this->pre_replace, $this->pre_content));
			$text = preg_replace_callback('/<pre[^>]*>.*<\\/pre>/ismU', array($this, 'pre_preg_callback'), $text, 1);
			$this->pre_content = '';
		}
	
	}
	
	
	protected function _convert_blockquotes(&$text)
	{
		$level = 0;
		$offset = 0;
		while (($start = stripos($text, '<blockquote', $offset)) !== false)
		{
			$offset = $start + 12;
			do
			{
				$end = stripos($text, '</blockquote>', $offset);
				$next = stripos($text, '<blockquote', $offset);
				if ($next !== false && $next < $end)
				{
					$offset = $next + 12;
					$level++;
				}
				
				if ($end !== false && $level > 0)
				{
					$offset = $end + 12;
					$level--;
				}
				else
				{
					if ($end !== false && $level == 0)
					{
						$taglen = strpos($text, '>', $start) - $start;
						$startpos = $start + $taglen + 1;
						$body = trim(substr($text, $startpos, $end - $startpos));
						$p_width = $this->width;
						if ($this->width > 0)
						{
							$this->width -= 2;
						}
						
						$this->_converter($body);
						$this->width = $p_width;
						$body = preg_replace_callback('/((?:^|\\n)>*)([^\\n]*)/', array($this, 'blockquote_citation_callback'), trim($body));
						$body = '<pre>' . htmlspecialchars($body) . '</pre>';
						$text = substr_replace($text, $body . "\n", $start, $end + 13 - $start);
						$offset = 0;
						break;
					}
					else
					{
						break;
					}
				
				}
			
			} while ($end || $next);
		
		}
	
	}
	
	
	public function blockquote_citation_callback($m)
	{
		$line = ltrim($m[2]);
		$space = $line[0] == '>' ? '' : ' ';
		return $m[1] . '>' . $space . $line;
	}
	
	
	public function tags_preg_callback($matches)
	{
		switch (strtolower($matches[1]))
		{
			case 'b':
			case 'strong':
				return $this->_toupper($matches[3]);
			case 'th':
				return $this->_toupper("\t\t" . $matches[3] . "\n");
			case 'h':
				return $this->_toupper("\n\n" . $matches[3] . "\n\n");
			case 'a':
				$url = str_replace(' ', '', $matches[3]);
				return $this->_build_link_list($url, $matches[4]);
		}
	
	}
	
	
	public function pre_preg_callback($matches)
	{
		return $this->pre_content;
	}
	
	
	private function _toupper($str)
	{
		$chunks = preg_split('/(<[^>]*>)/', $str, null, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		foreach ($chunks as $idx => $chunk)
		{
			if ($chunk[0] != '<')
			{
				$chunks[$idx] = $this->_strtoupper($chunk);
			}
		
		}
		
		return implode($chunks);
	}
	
	
	private function _strtoupper($str)
	{
		$str = html_entity_decode($str, ENT_COMPAT, $this->charset);
		$str = mb_strtoupper($str);
		$str = htmlspecialchars($str, ENT_COMPAT, $this->charset);
		return $str;
	}

}