<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * New Toolbar Button Class
 * 
 * @author		John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 */
class ComCategoriesToolbarButtonNew extends ComDefaultToolbarButtonNew
{
	public function getLink()
	{
		$option = KRequest::get('get.option', 'cmd');
		$view	= KInflector::singularize(KRequest::get('get.view', 'cmd'));
		
		return 'index.php?option='.$option.'&view='.$view.'&section='.KRequest::get('get.section','string');
	}
}
