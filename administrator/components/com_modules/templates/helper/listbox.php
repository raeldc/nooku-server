<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Listbox Template Helper
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules    
 */

class ComModulesTemplateHelperListbox extends KTemplateHelperListbox
{
	/**
	 * Customized to add a few attributes.
	 *
	 * @TODO propose making the onchange attribute the default?
	 * 
	 * @param 	array 	An optional array with configuration options
	 * @return	string	Html
	 * @see __call()
	 */
	protected function _listbox($config = array())
 	{
		$config = new KConfig($config);

		$config->append(array(
			//@TODO state isn't applied, work on patch later
			'state'		=> array(
				'client'	=> $config->client
			),
			'attribs'	=> array(
				'onchange' => 'this.form.submit()'
			)
		));

		return parent::_listbox($config);
 	}

 	/**
 	 * Renders menu assignment listbox
 	 *
 	 * @param 	array 	An optional array with configuration options
 	 * @return	string	Html
 	 */
 	public function pages($config = array())
 	{
 		$config = new KConfig($config);
 	}
}