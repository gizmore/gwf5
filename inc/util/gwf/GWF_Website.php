<?php
/**
 * General HTML utility.
 * @see GWF_Button
 * @see GWF_Box
 * @since 1.0 
 * @author spaceone
 * @author gizmore
 */
final class GWF_Website
{
	private static $_links = [];
// 	private static $_meta = array(
// 		'keywords' => array('keywords', '', 0),
// 		'description' => array('description', '', 0),
// 	);

	private static $_inline_css = '';
// 	private static $_output = '';


// 	public static function plaintext() { header('Content-Type: text/plain; charset=UTF-8'); }

	public static function redirectMessage($url, $time=12)
	{
		return self::redirect($url, $time);
	}
	
	public static function redirect($url, $time=0)
	{
		switch (GWF5::instance()->getFormat())
		{
			case 'html':
				if (GWF5::instance()->isAjax())
				{
					return new GWF_Response(self::ajaxRedirect($url));
				}
				else
				{
					if ($time > 0)
					{
						header("Refresh:$time;url=$url");
					}
					else
					{
						header('Location: ' . $url);
					}
					
					return GWF_Message::message('msg_redirect', [GWF_HTML::anchor($url), $time]);
				}
			case 'json': return new GWF_Response(['redirect' => $url, 'redirectWait' => $time]);
		}
	}
	private static function ajaxRedirect($url)
	{
		# Don't do this at home kids!
		return sprintf('<script type="text/javascript">setTimeout(function(){ window.location.href="%s" }, 1);</script>', $url);
	}

	public static function addInlineCSS($css) { self::$_inline_css .= $css; }
// 	public static function addMetaDescr($s) { self::$_meta['description'][1] .= $s; }
// 	public static function addMetaTags($s) { self::$_meta['keywords'][1] .= $s; }
// 	public static function addFeed($href, $title) { self::addLink($href, 'application/rss+xml', 'alternate'); }
	public static function addCSS($path, $media=0) { self::addLink($path, 'text/css', 'stylesheet', $media); }
	public static function addBowerCSS($path) { self::addCSS("/bower_components/$path"); }
	
// 	public static function setMetaTags($s) { self::$_meta['keywords'][1] = $s; }
// 	public static function setMetaDescr($s) { self::$_meta['description'][1] = $s; }
// 	public static function setPageTitle($title) { self::$_page_title = $title; }
// 	public static function setPageTitlePre($title) { self::$_page_title_pre = $title; }
// 	public static function setPageTitleAfter($title) { self::$_page_title_post = $title; }
// 	public static function getPageTitle() { return self::$_page_title; }

	/**
	 * add an html <link>
	 * @param string $type = mime_type
	 * @param mixed $rel relationship (one
	 * @param int $media
	 * @param string $href URL
	 * @see http://www.w3schools.com/tags/tag_link.asp
	 */
	public static function addLink($href, $type, $rel)
	{
		self::$_links[] = array(htmlspecialchars($href), $type, $rel);
	}

// 	# TODO: move
// 	public static function parseLink($href)
// 	{
// 		$url = parse_url(urldecode($href));
// 		$scheme = isset($url['scheme']) ? $url['scheme'].'://' : '';
// 		$host = isset($url['host']) ? htmlspecialchars($url['host']) : '';
// 		$port = isset($url['port']) ? sprintf(':%d', $url['port']) : '';
// 		$path = isset($url['path']) ? htmlspecialchars($url['path']) : '';
// 		$querystring = '';
// 		if (isset($url['query']))
// 		{
// 			$querystring .= '?';
// 			parse_str($url['query'], $query);
// 			foreach ($query as $k => $v)
// 			{
// 				$k = urlencode($k);
// 				if (is_array($v))
// 				{
// 					foreach($v as $vk => $vv)
// 					{
// 						$querystring .= sprintf('%s[%s]=&s&', $k, is_int($vk) ? '' : urlencode($vk), urlencode($vv));
// 					}
// 				}
// 				else
// 				{
// 					$querystring .= sprintf('%s=%s&', $k, urlencode($v));
// 				}
// 			}
// 			$querystring = htmlspecialchars(substr($querystring, 0, -1));
// 		}
// 		return $scheme . $host . $port . $path . $querystring;
// 	}

	/**
	 * Output of {$head_links}
	 * @return string
	 */
	public static function displayLink()
	{
		$back = '';
		foreach(self::$_links as $link)
		{
			list($href, $type, $rel) = $link;
			$back .= sprintf('<link rel="%s" type="%s" href="%s" />'."\n", $rel, $type, $href);
		}
		# embedded CSS (move?)
		if('' !== self::$_inline_css)
		{
			$back .= sprintf("\n\t<style><!--\n\t%s\n\t--></style>\n", self::indent(self::$_inline_css, 2));
		}
		return $back;
	}

// 	/**
// 	 * You can not specify media and title with this function
// 	 * @param array $paths
// 	 * @param type $pre
// 	 * @param type $after 
// 	 */
// 	public static function addCSSA(array $paths, $pre='', $after='')
// 	{
// 		foreach($paths as $path)
// 		{
// 			self::addCSS($pre.$path.$after);
// 		}
// 	}

	/**
	 * add an html <meta> tag
	 * @param array $meta = array($name, $content, 0=>name;1=>http-equiv);
	 * @param boolean $overwrite overwrite key if exist?
	 * @return boolean false if metakey was not overwritten, otherwise true
	 * @todo possible without key but same functionality?
	 * @todo strings as params? addMeta($name, $content, $mode, $overwrite)
	 */
	public static function addMeta(array $metaA, $overwrite=false)
	{
		if((false === $overwrite) && (isset(self::$_meta[$metaA[0]]) === true))
		{
			return false;
		}
		self::$_meta[$metaA[0]] = $metaA;
		//self::$_meta[$name] = array($name, $content, $mode);
		return true;
	}

	public static function addMetaA(array $metaA)
	{
		foreach($metaA as $meta)
		{
			self::addMeta($meta);
		}
	}

	/**
	 *
	 * @see addMeta()
	 */
	public static function displayMeta()
	{
		$back = '';
		$mode = array('name', 'http-equiv');
		foreach (self::$_meta as $meta)
		{
			if (!is_array($meta))
			{
				continue; # TODO: spaceone fix.
			}
			list($name, $content, $equiv) = $meta;
 			$equiv = $mode[$equiv];
			$back .= sprintf('<meta %s="%s" content="%s"%s', $equiv, $name, $content, self::$xhtml);
		}
		return $back;
	}


	####################
	### Display Page ###
	####################
}
