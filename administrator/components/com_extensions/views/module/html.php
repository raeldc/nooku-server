<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Modules HTML View Class
 *   
 * @author    	Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
 */
class ComExtensionsViewModuleHtml extends ComDefaultViewHtml
{
	public function display()
	{
	    $module = $this->getModel()->getItem();
	   
	    if($module->application == 'site') {
	        JFactory::getLanguage()->load($module->type, JPATH_SITE );
	    } else {
		    JFactory::getLanguage()->load($module->type);
	    }
		
		return parent::display();
	}
}