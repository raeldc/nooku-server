<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Articls Database Table class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */
class ComArticlesDatabaseTableArticles extends KDatabaseTableDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
        	'identity_column' => 'id',
            'name' => 'content',
            'behaviors' => array(
            	'creatable', 'modifiable', 'lockable', 'orderable', 'sluggable', 'revisable' 
            ),
            'column_map' => array(
                'locked_on'        => 'checked_out_time',
                'locked_by'        => 'checked_out',
                'slug'       	   => 'alias',
                'section_id'       => 'sectionid',
                'category_id'	   => 'catid',
                'created_on' 	   => 'created',
                'modified_on'      => 'modified',
                'description'      => 'metadesc',
                'params'		   => 'attribs'
            ),
            'filters' => array(
                'introtext'   => array('html', 'tidy'),
                'fulltext'    => array('html', 'tidy'),
                'attribs'	  => 'ini'
		    )
        ));

        parent::_initialize($config);
    }
}