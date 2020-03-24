<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
Swift_DependencyContainer::getInstance()->register('cache')->asAliasOf('cache.array')->register('tempdir')->asValue('/tmp')->register('cache.null')->asSharedInstanceOf('Swift_KeyCache_NullKeyCache')->register('cache.array')->asSharedInstanceOf('Swift_KeyCache_ArrayKeyCache')->withDependencies(array('cache.inputstream'))->register('cache.disk')->asSharedInstanceOf('Swift_KeyCache_DiskKeyCache')->withDependencies(array('cache.inputstream', 'tempdir'))->register('cache.inputstream')->asNewInstanceOf('Swift_KeyCache_SimpleKeyCacheInputStream');