<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */


/**
 * Date Helper
.*
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @uses		KFactory
 */
class ComDefaultTemplateHelperBehavior extends KTemplateHelperBehavior
{
	/**
	 * Keep session alive 
	 * 
	 * This will send an ascynchronous request to the server via AJAX on an interval
	 *
	 * @return string	The html output
	 */
	public function keepalive($config = array())
	{
		//Get the config session lifetime
		$lifetime = KFactory::get('lib.joomla.session')->getExpire() * 60000;
		
		//Refresh time is 1 minute less than the liftime
		$refresh =  ($lifetime <= 60000) ? 30000 : $lifetime - 60000;
		
        $config = new KConfig($config);
		$config->append(array(
			'refresh' => $refresh
 		));
		
	    return parent::keepalive($config);
	}
}