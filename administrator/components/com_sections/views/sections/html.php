<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * View HTML Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections  
 */
class ComSectionsViewSectionsHtml extends ComSectionsViewHtml
{
	
	public function display()
	{
		KFactory::get('admin::com.sections.toolbar.sections', array(
			'title' => 'Section Manager',
			'icon'  => 'sections.png' ))
			->append('divider')     
			->append(KFactory::tmp('admin::com.sections.toolbar.button.enable', array('text' => 'publish')))
			->append(KFactory::tmp('admin::com.sections.toolbar.button.disable', array('text' => 'unpublish')))
			->append('divider')
			->append('edit');
                                        
		return parent::display();
	}
	
}
