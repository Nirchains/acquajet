<?php defined("_JEXEC") or die(file_get_contents("index.html"));

/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */


/**
 * Class FoxHtmlUserAgentParser
 * Based on UAParser.js 0.7.7 commit 1fa7137f1f311043ccf65bb94ffc5ce5f273f77f
 */
class FoxHtmlUserAgentParser
{
	///////////////
	// String map
	//////////////

	private $aryMaps = array(

		'browser' => array(
			'oldsafari' => array(
				'version' => array(
					'1.0' => '/8',
					'1.2' => '/1',
					'1.3' => '/3',
					'2.0' => '/412',
					'2.0.2' => '/416',
					'2.0.3' => '/417',
					'2.0.4' => '/419',
					'?' => '/'
				)
			)
		),

		'device' => array(
			'amazon' => array(
				'model' => array(
					'Fire Phone' => array('SD', 'KF')
				)
			),
			'sprint' => array(
				'model' => array(
					'Evo Shift 4G' => '7373KT'
				),
				'vendor' => array(
					'HTC' => 'APA',
					'Sprint' => 'Sprint'
				)
			)
		),

		'os' => array(
			'windows' => array(
				'version' => array(
					'ME' => '4.90',
					'NT 3.11' => 'NT3.51',
					'NT 4.0' => 'NT4.0',
					'2000' => 'NT 5.0',
					'XP' => array('NT 5.1', 'NT 5.2'),
					'Vista' => 'NT 6.0',
					'7' => 'NT 6.1',
					'8' => 'NT 6.2',
					'8.1' => 'NT 6.3',
					'10' => array('NT 6.4', 'NT 10.0'),
					'RT' => 'ARM'
				)
			)
		)
	);

	//////////////
	// Regex map
	/////////////
	private $aryRegexes = array();

	//default properties
	private $aryBrowserProp = array('NAME' => '-', 'VERSION' => '-');
	private $aryOS_Prop = array('NAME' => '-', 'VERSION' => '-');
	private $aryDeviceProp = array('MODEL' => '-', 'VENDOR' => '-', 'TYPE' => '-');

	//String variable which contains user agent string
	private $strUserAgent;


	function __construct($strOperateUA = '')
	{
		$this->aryRegexes = array(

			'browser' => array(
				array(

					// Presto based
					'/(opera\smini)\/([\w\.-]+)/i',
					// Opera Mini
					'/(opera\s[mobiletab]+).+version\/([\w\.-]+)/i',
					// Opera Mobi/Tablet
					'/(opera).+version\/([\w\.]+)/i',
					// Opera > 9.80
					'/(opera)[\/\s]+([\w\.]+)/i'
					// Opera < 9.80

				),
				array(
					'NAME',
					'VERSION'
				),
				array(

					'/\s(opr)\/([\w\.]+)/i'
					// Opera Webkit
				),
				array(
					array(
						'NAME',
						'Opera'
					),
					'VERSION'
				),
				array(

					// Mixed
					'/(kindle)\/([\w\.]+)/i',
					// Kindle
					'/(lunascape|maxthon|netfront|jasmine|blazer)[\/\s]?([\w\.]+)*/i',
					// Lunascape/Maxthon/Netfront/Jasmine/Blazer

					// Trident based
					'/(avant\s|iemobile|slim|baidu)(?:browser)?[\/\s]?([\w\.]*)/i',
					// Avant/IEMobile/SlimBrowser/Baidu
					'/(?:ms|\()(ie)\s([\w\.]+)/i',
					// Internet Explorer

					// Webkit/KHTML based
					'/(rekonq)\/([\w\.]+)*/i',
					// Rekonq
					'/(chromium|flock|rockmelt|midori|epiphany|silk|skyfire|ovibrowser|bolt|iron|vivaldi)\/([\w\.-]+)/i'
					// Chromium/Flock/RockMelt/Midori/Epiphany/Silk/Skyfire/Bolt/Iron/vivaldi
				),
				array(
					'NAME',
					'VERSION'
				),
				array(

					'/(trident).+rv[:\s]([\w\.]+).+like\sgecko/i',
					// IE11
					'/(Edge)\/((\d+)?[\w\.]+)/i'
					// IE12
				),
				array(
					array(
						'NAME',
						'IE'
					),
					'VERSION'
				),
				array(

					'/(yabrowser)\/([\w\.]+)/i'
					// Yandex
				),
				array(
					array(
						'NAME',
						'Yandex'
					),
					'VERSION'
				),
				array(

					'/(comodo_dragon)\/([\w\.]+)/i'
					// Comodo Dragon
				),
				array(
					array(
						'NAME',
						'/_/i',
						' '
					),
					'VERSION'
				),
				array(

					'/(chrome|omniweb|arora|[tizenoka]{5}\s?browser)\/v?([\w\.]+)/i',
					// Chrome/OmniWeb/Arora/Tizen/Nokia
					'/(uc\s?browser|qqbrowser)[\/\s]?([\w\.]+)/i'
					//UCBrowser/QQBrowser
				),
				array(
					'NAME',
					'VERSION'
				),
				array(

					'/(dolfin)\/([\w\.]+)/i'
					// Dolphin
				),
				array(
					array(
						'NAME',
						'Dolphin'
					),
					'VERSION'
				),
				array(

					'/((?:android.+)crmo|crios)\/([\w\.]+)/i'
					// Chrome for Android/iOS
				),
				array(
					array(
						'NAME',
						'Chrome'
					),
					'VERSION'
				),
				array(

					'/XiaoMi\/MiuiBrowser\/([\w\.]+)/i'
					// MIUI Browser
				),
				array(
					'VERSION',
					array(
						'NAME',
						'MIUI Browser'
					)
				),
				array(

					'/android.+version\/([\w\.]+)\s+(?:mobile\s?safari|safari)/i'
					// Android Browser
				),
				array(
					'VERSION',
					array(
						'NAME',
						'Android Browser'
					)
				),
				array(

					'/FBAV\/([\w\.]+);/i'
					// Facebook App for iOS
				),
				array(
					'VERSION',
					array(
						'NAME',
						'Facebook'
					)
				),
				array(

					'/version\/([\w\.]+).+?mobile\/\w+\s(safari)/i'
					// Mobile Safari
				),
				array(
					'VERSION',
					array(
						'NAME',
						'Mobile Safari'
					)
				),
				array(

					'/version\/([\w\.]+).+?(mobile\s?safari|safari)/i'
					// Safari & Safari Mobile
				),
				array(
					'VERSION',
					'NAME'
				),
				array(

					'/webkit.+?(mobile\s?safari|safari)(\/[\w\.]+)/i'
					// Safari < 3.0
				),
				array(
					'NAME',
					array(
						'VERSION',
						'mapper_str',
						$this->aryMaps['browser']['oldsafari']['version']
					)
				),
				array(

					'/(konqueror)\/([\w\.]+)/i',
					// Konqueror
					'/(webkit|khtml)\/([\w\.]+)/i'
				),
				array(
					'NAME',
					'VERSION'
				),
				array(

					// Gecko based
					'/(navigator|netscape)\/([\w\.-]+)/i'
					// Netscape
				),
				array(
					array(
						'NAME',
						'Netscape'
					),
					'VERSION'
				),
				array(
					'/(swiftfox)/i',
					// Swiftfox
					'/(icedragon|iceweasel|camino|chimera|fennec|maemo\sbrowser|minimo|conkeror)[\/\s]?([\w\.\+]+)/i',
					// IceDragon/Iceweasel/Camino/Chimera/Fennec/Maemo/Minimo/Conkeror
					'/(firefox|seamonkey|k-meleon|icecat|iceape|firebird|phoenix)\/([\w\.-]+)/i',
					// Firefox/SeaMonkey/K-Meleon/IceCat/IceApe/Firebird/Phoenix
					'/(mozilla)\/([\w\.]+).+rv\:.+gecko\/\d+/i',
					// Mozilla

					// Other
					'/(polaris|lynx|dillo|icab|doris|amaya|w3m|netsurf)[\/\s]?([\w\.]+)/i',
					// Polaris/Lynx/Dillo/iCab/Doris/Amaya/w3m/NetSurf
					'/(links)\s\(([\w\.]+)/i',
					// Links
					'/(gobrowser)\/?([\w\.]+)*/i',
					// GoBrowser
					'/(ice\s?browser)\/v?([\w\._]+)/i',
					// ICE Browser
					'/(mosaic)[\/\s]([\w\.]+)/i'
					// Mosaic
				),
				array(
					'NAME',
					'VERSION'
				)

			),
			'device' => array(
				array(

					'/\((ipad|playbook);[\w\s\);-]+(rim|apple)/i'
					// iPad/PlayBook
				),
				array(
					'MODEL',
					'VENDOR',
					array(
						'TYPE',
						'TABLET'
					)
				),
				array(

					'/applecoremedia\/[\w\.]+ \((ipad)/'
					// iPad
				),
				array(
					'MODEL',
					array(
						'VENDOR',
						'Apple'
					),
					array(
						'TYPE',
						'TABLET'
					)
				),
				array(

					'/(apple\s{0,1}tv)/i'
					// Apple TV
				),
				array(
					array(
						'MODEL',
						'Apple TV'
					),
					array(
						'VENDOR',
						'Apple'
					)
				),
				array(
					'/(archos)\s(gamepad2?)/i',
					// Archos
					'/(hp).+(touchpad)/i',
					// HP TouchPad
					'/(kindle)\/([\w\.]+)/i',
					// Kindle
					'/\s(nook)[\w\s]+build\/(\w+)/i',
					// Nook
					'/(dell)\s(strea[kpr\s\d]*[\dko])/i'
					// Dell Streak
				),
				array(
					'VENDOR',
					'MODEL',
					array(
						'TYPE',
						'TABLET'
					)
				),
				array(

					'/(kf[A-z]+)\sbuild\/[\w\.]+.*silk\//i'
					// Kindle Fire HD
				),
				array(
					'MODEL',
					array(
						'VENDOR',
						'Amazon'
					),
					array(
						'TYPE',
						'TABLET'
					)
				),
				array(
					'/(sd|kf)[0349hijorstuw]+\sbuild\/[\w\.]+.*silk\//i'
					// Fire Phone
				),
				array(
					array(
						'MODEL',
						'mapper_str',
						$this->aryMaps['device']['amazon']['model']
					),
					array(
						'VENDOR',
						'Amazon'
					),
					array(
						'TYPE',
						'MOBILE'
					)
				),
				array(

					'/\((ip[honed|\s\w*]+);.+(apple)/i'
					// iPod/iPhone
				),
				array(
					'MODEL',
					'VENDOR',
					array(
						'TYPE',
						'MOBILE'
					)
				),
				array(
					'/\((ip[honed|\s\w*]+);/i'
					// iPod/iPhone
				),
				array(
					'MODEL',
					array(
						'VENDOR',
						'Apple'
					),
					array(
						'TYPE',
						'MOBILE'
					)
				),
				array(

					'/(blackberry)[\s-]?(\w+)/i',
					// BlackBerry
					'/(blackberry|benq|palm(?=\-)|sonyericsson|acer|asus|dell|huawei|meizu|motorola|polytron)[\s_-]?([\w-]+)*/i',
					// BenQ/Palm/Sony-Ericsson/Acer/Asus/Dell/Huawei/Meizu/Motorola/Polytron
					'/(hp)\s([\w\s]+\w)/i',
					// HP iPAQ
					'/(asus)-?(\w+)/i'
					// Asus
				),
				array(
					'VENDOR',
					'MODEL',
					array(
						'TYPE',
						'MOBILE'
					)
				),
				array(
					'/\(bb10;\s(\w+)/i'
					// BlackBerry 10
				),
				array(
					'MODEL',
					array(
						'VENDOR',
						'BlackBerry'
					),
					array(
						'TYPE',
						'MOBILE'
					)
				),
				array(
					// Asus Tablets
					'/android.+(transfo[prime\s]{4,10}\s\w+|eeepc|slider\s\w+|nexus 7)/i'
				),
				array(
					'MODEL',
					array(
						'VENDOR',
						'Asus'
					),
					array(
						'TYPE',
						'TABLET'
					)
				),
				array(

					'/(sony)\s(tablet\s[ps])\sbuild\//i',
					// Sony Tablets
					'/(sony)?(?:sgp.+)\sbuild\//i'
				),
				array(
					array(
						'VENDOR',
						'Sony'
					),
					array(
						'MODEL',
						'Xperia Tablet'
					),
					array(
						'TYPE',
						'TABLET'
					)
				),
				array(
					'/(?:sony)?(?:(?:(?:c|d)\d{4})|(?:so[-l].+))\sbuild\//i'
				),
				array(
					array(
						'VENDOR',
						'Sony'
					),
					array(
						'MODEL',
						'Xperia Phone'
					),
					array(
						'TYPE',
						'MOBILE'
					)
				),
				array(
					'/\s(ouya)\s/i',
					// Ouya
					'/(nintendo)\s([wids3u]+)/i'
					// Nintendo
				),
				array(
					'VENDOR',
					'MODEL',
					array(
						'TYPE',
						'CONSOLE'
					)
				),
				array(

					'/android.+;\s(shield)\sbuild/i'
					// Nvidia
				),
				array(
					'MODEL',
					array(
						'VENDOR',
						'Nvidia'
					),
					array(
						'TYPE',
						'CONSOLE'
					)
				),
				array(

					'/(playstation\s[3portablevi]+)/i'
					// Playstation
				),
				array(
					array(
						'VENDOR',
						'Sony'
					),
					'MODEL',
					array(
						'TYPE',
						'CONSOLE'
					)
				),
				array(

					'/(sprint\s(\w+))/i'
					// Sprint Phones
				),
				array(
					array(
						'VENDOR',
						'mapper_str',
						$this->aryMaps['device']['sprint']['vendor']
					),
					array(
						'MODEL',
						'mapper_str',
						$this->aryMaps['device']['sprint']['model']
					),
					array(
						'TYPE',
						'MOBILE'
					)
				),
				array(

					'/(lenovo)\s?(S(?:5000|6000)+(?:[-][\w+]))/i'
					// Lenovo tablets
				),
				array(
					array(
						'VENDOR',
						'Lenovo'
					),
					'MODEL',
					array(
						'TYPE',
						'TABLET'
					)
				),
				array(

					'/(htc)[;_\s-]+([\w\s]+(?=\))|\w+)*/i',
					// HTC
					'/(zte)-(\w+)*/i',
					// ZTE
					'/(alcatel|geeksphone|huawei|lenovo|nexian|panasonic|(?=;\s)sony)[_\s-]?([\w-]+)*/i'
					// Alcatel/GeeksPhone/Huawei/Lenovo/Nexian/Panasonic/Sony
				),
				array(
					'VENDOR',
					array(
						'MODEL',
						'/_/i',
						' '
					),
					array(
						'TYPE',
						'MOBILE'
					)
				),
				array(

					'/(nexus\s9)/i'
					// HTC Nexus 9
				),
				array(
					'MODEL',
					array(
						'VENDOR',
						'HTC'
					),
					array(
						'TYPE',
						'TABLET'
					)
				),
				array(

					'/[\s\(;](xbox(?:\sone)?)[\s\);]/i'
					// Microsoft Xbox
				),
				array(
					'MODEL',
					array(
						'VENDOR',
						'Microsoft'
					),
					array(
						'TYPE',
						'CONSOLE'
					)
				),
				array(
					'/(kin\.[onetw]{3})/i'
					// Microsoft Kin
				),
				array(
					array(
						'MODEL',
						'/\./',
						' '
					),
					array(
						'VENDOR',
						'Microsoft'
					),
					array(
						'TYPE',
						'MOBILE'
					)
				),
				array(

					// Motorola
					'/\s(milestone|droid(?:[2-4x]|\s(?:bionic|x2|pro|razr))?(:?\s4g)?)[\w\s]+build\//i',
					'/mot[\s-]?(\w+)*/i',
					'/(XT\d{3,4}) build\//i'
				),
				array(
					'MODEL',
					array(
						'VENDOR',
						'Motorola'
					),
					array(
						'TYPE',
						'MOBILE'
					)
				),
				array(
					'/android.+\s((mz60\d|xoom[\s2]{0,2}))\sbuild\//i'
				),
				array(
					'MODEL',
					array(
						'VENDOR',
						'Motorola'
					),
					array(
						'TYPE',
						'TABLET'
					)
				),
				array(

					'/android.+((sch-i[89]0\d|shw-m380s|gt-p\d{4}|gt-n8000|sgh-t8[56]9|nexus 10))/i',
					'/((SM-T\w+))/i'
				),
				array(
					array(
						'VENDOR',
						'Samsung'
					),
					'MODEL',
					array(
						'TYPE',
						'TABLET'
					)
				),
				array(                  // Samsung
					'/((s[cgp]h-\w+|gt-\w+|galaxy\snexus|sm-n900))/i',
					'/(sam[sung]*)[\s-]*(\w+-?[\w-]*)*/i',
					'/sec-((sgh\w+))/i'
				),
				array(
					array(
						'VENDOR',
						'Samsung'
					),
					'MODEL',
					array(
						'TYPE',
						'MOBILE'
					)
				),
				array(
					'/(samsung);smarttv/i'
				),
				array(
					'VENDOR',
					'MODEL',
					array(
						'TYPE',
						'SMARTTV'
					)
				),
				array(

					'/\(dtv[\);].+(aquos)/i'
					// Sharp
				),
				array(
					'MODEL',
					array(
						'VENDOR',
						'Sharp'
					),
					array(
						'TYPE',
						'SMARTTV'
					)
				),
				array(
					'/sie-(\w+)*/i'
					// Siemens
				),
				array(
					array(
						'VENDOR',
						'Siemens'
					),
					'MODEL',
					array(
						'TYPE',
						'MOBILE'
					)
				),
				array(

					'/(maemo|nokia).*(n900|lumia\s\d+)/i',
					// Nokia
					'/(nokia)[\s_-]?([\w-]+)*/i',
				),
				array(
					array(
						'VENDOR',
						'Nokia'
					),
					'MODEL',
					array(
						'TYPE',
						'MOBILE'
					)
				),
				array(

					'/android\s3\.[\s\w;-]{10}(a\d{3})/i'
					// Acer
				),
				array(
					'MODEL',
					array(
						'VENDOR',
						'Acer'
					),
					array(
						'TYPE',
						'TABLET'
					)
				),
				array(

					'/android\s3\.[\s\w;-]{10}(lg?)-([06cv9]{3,4})/i'
					// LG Tablet
				),
				array(
					array(
						'VENDOR',
						'LG'
					),
					'MODEL',
					array(
						'TYPE',
						'TABLET'
					)
				),
				array(
					'/(lg) netcast\.tv/i'
					// LG SmartTV
				),
				array(
					'VENDOR',
					array(
						'TYPE',
						'SMARTTV'
					)
				),
				array(
					'/(nexus\s[45])/i',
					// LG
					'/lg[e;\s\/-]+(\w+)*/i'
				),
				array(
					'MODEL',
					array(
						'VENDOR',
						'LG'
					),
					array(
						'TYPE',
						'MOBILE'
					)
				),
				array(

					'/android.+(ideatab[a-z0-9\-\s]+)/i'
					// Lenovo
				),
				array(
					'MODEL',
					array(
						'VENDOR',
						'Lenovo'
					),
					array(
						'TYPE',
						'TABLET'
					)
				),
				array(

					'/linux;.+((jolla));/i'
					// Jolla
				),
				array(
					'VENDOR',
					'MODEL',
					array(
						'TYPE',
						'MOBILE'
					)
				),
				array(
					'/((pebble))app\/[\d\.]+\s/i'
					// Pebble
				),
				array(
					'VENDOR',
					'MODEL',
					array(
						'TYPE',
						'WEARABLE'
					)
				),
				array(

					'/android.+;\s(glass)\s\d/i'
					// Google Glass
				),
				array(
					'MODEL',
					array(
						'VENDOR',
						'Google'
					),
					array(
						'TYPE',
						'WEARABLE'
					)
				),
				array(

					'/android.+(\w+)\s+build\/hm\1/i',
					// Xiaomi Hongmi 'numeric' models
					'/android.+(hm[\s\-_]*note?[\s_]*(?:\d\w)?)\s+build/i',
					// Xiaomi Hongmi
					'/android.+(mi[\s\-_]*(?:one|one[\s_]plus)?[\s_]*(?:\d\w)?)\s+build/i'
					// Xiaomi Mi
				),
				array(
					array(
						'MODEL',
						'/_/',
						' '
					),
					array(
						'VENDOR',
						'Xiaomi'
					),
					array(
						'TYPE',
						'MOBILE'
					)
				),
				array(

					'/(mobile|tablet);.+rv\:.+gecko\//i'
					// Unidentifiable
				),
				array(
					array(
						'TYPE',
						'util.lowerize'
					),
					'VENDOR',
					'MODEL'
				)
			)
		,

			'os' => array(
				array(

					// Windows based
					'/microsoft\s(windows)\s(vista|xp)/i'
					// Windows (iTunes)
				),
				array(
					'NAME',
					'VERSION'
				),
				array(
					'/(windows)\snt\s6\.2;\s(arm)/i',
					// Windows RT
					'/(windows\sphone(?:\sos)*|windows\smobile|windows)[\s\/]?([ntce\d\.\s]+\w)/i'
				),
				array(
					'NAME',
					array(
						'VERSION',
						'mapper_str',
						$this->aryMaps['os']['windows']['version']
					)
				),
				array(
					'/(win(?=3|9|n)|win\s9x\s)([nt\d\.]+)/i'
				),
				array(
					array(
						'NAME',
						'Windows'
					),
					array(
						'VERSION',
						'mapper_str',
						$this->aryMaps['os']['windows']['version']
					)
				),
				array(

					// Mobile/Embedded OS
					'/\((bb)(10);/i'
					// BlackBerry 10
				),
				array(
					array(
						'NAME',
						'BlackBerry'
					),
					'VERSION'
				),
				array(
					'/(blackberry)\w*\/?([\w\.]+)*/i',
					// Blackberry
					'/(tizen)[\/\s]([\w\.]+)/i',
					// Tizen
					'/(android|webos|palm\sos|qnx|bada|rim\stablet\sos|meego|contiki)[\/\s-]?([\w\.]+)*/i',
					// Android/WebOS/Palm/QNX/Bada/RIM/MeeGo/Contiki
					'/linux;.+(sailfish);/i'
					// Sailfish OS
				),
				array(
					'NAME',
					'VERSION'
				),
				array(
					'/(symbian\s?os|symbos|s60(?=;))[\/\s-]?([\w\.]+)*/i'
					// Symbian
				),
				array(
					array(
						'NAME',
						'Symbian'
					),
					'VERSION'
				),
				array(
					'/\((series40);/i'
					// Series 40
				),
				array('NAME'),
				array(
					'/mozilla.+\(mobile;.+gecko.+firefox/i'
					// Firefox OS
				),
				array(
					array(
						'NAME',
						'Firefox OS'
					),
					'VERSION'
				),
				array(

					// Console
					'/(nintendo|playstation)\s([wids3portablevu]+)/i',
					// Nintendo/Playstation

					// GNU/Linux based
					'/(mint)[\/\s\(]?(\w+)*/i',
					// Mint
					'/(mageia|vectorlinux)[;\s]/i',
					// Mageia/VectorLinux
					'/(joli|[kxln]?ubuntu|debian|[open]*suse|gentoo|arch|slackware|fedora|mandriva|centos|pclinuxos|redhat|zenwalk)[\/\s-]?([\w\.-]+)*/i',
					// Joli/Ubuntu/Debian/SUSE/Gentoo/Arch/Slackware
					// Fedora/Mandriva/CentOS/PCLinuxOS/RedHat/Zenwalk
					'/(hurd|linux)\s?([\w\.]+)*/i',
					// Hurd/Linux
					'/(gnu)\s?([\w\.]+)*/i'
					// GNU
				),
				array(
					'NAME',
					'VERSION'
				),
				array(

					'/(cros)\s[\w]+\s([\w\.]+\w)/i'
					// Chromium OS
				),
				array(
					array(
						'NAME',
						'Chromium OS'
					),
					'VERSION'
				),
				array(

					// Solaris
					'/(sunos)\s?([\w\.]+\d)*/i'
					// Solaris
				),
				array(
					array(
						'NAME',
						'Solaris'
					),
					'VERSION'
				),
				array(

					// BSD based
					'/\s([frentopc-]{0,4}bsd|dragonfly)\s?([\w\.]+)*/i'
					// FreeBSD/NetBSD/OpenBSD/PC-BSD/DragonFly
				),
				array(
					'NAME',
					'VERSION'
				),
				array(

					'/(ip[honead]+)(?:.*os\s*([\w]+)*\slike\smac|;\sopera)/i'
					// iOS
				),
				array(
					array(
						'NAME',
						'iOS'
					),
					array(
						'VERSION',
						'/_/i',
						'.'
					)
				),
				array(
					'/(mac\sos\sx)\s?([\w\s\.]+\w)*/i',
					'/(macintosh|mac(?=_powerpc)\s)/i'
					// Mac OS
				),
				array(
					array(
						'NAME',
						'Mac OS'
					),
					array(
						'VERSION',
						'/_/i',
						'.'
					)
				),
				array(

					// Other
					'/((?:open)?solaris)[\/\s-]?([\w\.]+)*/i',
					// Solaris
					'/(haiku)\s(\w+)/i',
					// Haiku
					'/(aix)\s((\d)(?=\.|\)|\s)[\w\.]*)*/i',
					// AIX
					'/(plan\s9|minix|beos|os\/2|amigaos|morphos|risc\sos|openvms)/i',
					//  Plan9/Minix/BeOS/OS2/AmigaOS/MorphOS/RISCOS/OpenVMS
					'/(unix)\s?([\w\.]+)*/i'
					// UNIX
				),
				array(
					'NAME',
					'VERSION'
				)
			)

		);
		$this->strUserAgent = $strOperateUA;
	}


	//Set the current user agent
	public function setUA($strOperateUA)
	{
		$this->strUserAgent = $strOperateUA;
	}


	//return the browser details
	public function getBrowser()
	{
		$aryBrowser = array_merge($this->aryBrowserProp, $this->applyRegex($this->aryRegexes['browser']));

		//Get the major version from the complete version number
		$parts = explode('.', $aryBrowser['VERSION']);
		$aryBrowser['major'] = $parts[0];

		return $aryBrowser;
	}


	//return the os details
	public function getOS()
	{
		return array_merge($this->aryOS_Prop, $this->applyRegex($this->aryRegexes['os']));
	}


	//return the device details
	public function getDevice()
	{
		return array_merge($this->aryDeviceProp, $this->applyRegex($this->aryRegexes['device']));
	}


	//Verify each regular expression
	private function applyRegex($aryRegexSet)
	{
		if ('' == $this->strUserAgent) return array();
		for ($cntrI = 0; $cntrI < count($aryRegexSet); $cntrI += 2)
		{

			for ($cntrJ = 0; $cntrJ < count($aryRegexSet[$cntrI]); $cntrJ++)
			{
				if (0 < preg_match_all($aryRegexSet[$cntrI][$cntrJ], $this->strUserAgent, $aryMatches))
				{
					$aryResultProp = $aryRegexSet[$cntrI + 1];

					$aryResult = array();

					for ($cntrK = 0; $cntrK < count($aryResultProp); $cntrK++)
					{
						if (!isset($aryMatches[$cntrK + 1][0]))
						{
							$strFinalValue = '';
						}
						else
						{
							$strFinalValue = $aryMatches[$cntrK + 1][0];
						}
						if (is_array($aryResultProp[$cntrK]))
						{

							switch (count($aryResultProp[$cntrK]))
							{
								case 2:
									if (function_exists($aryResultProp[$cntrK][1]))
									{
										// assign modified match
										$strFinalValue = $$aryResultProp[$cntrK][1]($strFinalValue);
									}
									else
									{
										// assign given value, ignore regex match
										$strFinalValue = $aryResultProp[$cntrK][1];
									}
									break;
								case 3:
									// check whether function or regex

									if (function_exists($aryResultProp[$cntrK][1]))
									{
										// call function (usually string mapper)
										$strFinalValue = $aryResultProp[$cntrK][1]($strFinalValue, $aryResultProp[$cntrK][2]);
									}
									else
									{
										// sanitize match using given regex
										$strFinalValue = preg_replace($aryResultProp[$cntrK][1], $aryResultProp[$cntrK][2], $strFinalValue);
									}
									break;
								case 4:
									if (function_exists($aryResultProp[$cntrK][3]))
									{
										$strFinalValue = $$aryResultProp[$cntrK][3]($strFinalValue, $aryResultProp[$cntrK][1], $aryResultProp[$cntrK][2]);
									}
							}

							$aryResult[$aryResultProp[$cntrK][0]] = $strFinalValue;
						}
						else
						{
							$aryResult[$aryResultProp[$cntrK]] = $strFinalValue;
						}
					}//Populate properties of array depending on the type of element
					return ($aryResult);

					break;
				}
			}//Loop through each regex

		}//Loop through each regex set
		return array();
	}
}//Class for parsing User Agent ends

//Replace final value with more user friendly details
function mapper_str($strToCheck, $aryMap)
{

	foreach ($aryMap as $keyMap => $valMap)
	{
		// check if array
		if (is_array($valMap) && count($valMap) > 0)
		{
			foreach ($valMap as $valStrMap)
			{
				if (false !== stripos($strToCheck, $valStrMap))
				{
					return $keyMap;
				}
			}
		}
		else
		{

			if (false !== stripos($strToCheck, $valMap))
			{
				return $keyMap;
			}
		}
	}
	return $strToCheck;
}
