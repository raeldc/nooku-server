<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2009 - 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Component Loader
 *
 * @author    	Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @category 	Nooku
 * @package     Nooku_Components
 * @subpackage  Weblinks
 */

KLoader::load('site::com.weblinks.mappings');

echo KFactory::get('site::com.weblinks.dispatcher')->dispatch();