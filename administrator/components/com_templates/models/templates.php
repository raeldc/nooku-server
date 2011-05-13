<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Templates Model Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates   
 */
class ComTemplatesModelTemplates extends KModelAbstract
{
    /**
     * Constructor
     *A
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_state
            ->insert('application', 'cmd', 'site')
            ->insert('name'       , 'cmd', null, true)
            ->insert('limit'      , 'int')
            ->insert('offset'     , 'int')
            ->insert('sort'       , 'cmd')
            ->insert('direction'  , 'word', 'asc')
            ->insert('search'     , 'string');
    }

    /**
     * Method to get a item
     *
     * @return KDatabaseRowInterface
     */
    public function getItem()
    {
        if(!isset($this->_item))
        {
            $base = $this->_state->application == 'admin' ? JPATH_ADMINISTRATOR : JPATH_SITE;
            $path = $base.'/templates/'.$this->_state->name;

            if(!file_exists($path.'/templateDetails.xml')) return $this->_item = null;

            $data = array(
                'path'        => $path,
                'application' => $this->_state->application
            );

            $this->_item = KFactory::tmp('admin::com.templates.database.row.template', array('data' => $data));
        }
        
        return $this->_item;
    }

    /**
     * Get a list of items
     *
     * @return KDatabaseRowsetInterface
     */
    public function getList()
    { 
        if(!isset($this->_list))
        {
            $data = array();
            $base = $this->_state->application == 'admin' ? JPATH_ADMINISTRATOR : JPATH_SITE;
            $path = $base.'/templates';

            foreach(new DirectoryIterator($path) as $folder)
            {
                if($folder->isDir())
                {
                    $name_mismatch   = $this->_state->name && $this->_state->name != $folder->getFilename();
                    $search_mismatch = $this->_state->search && strpos($folder->getFilename(), $this->_state->search) === false;
                    $file_exists     = file_exists($folder->getRealPath().'/templateDetails.xml');
                    
                    if($name_mismatch || $search_mismatch || !$file_exists) continue;
                    
                    $data[] = array(
                        'path'        => $folder->getRealPath(),
                        'application' => $this->_state->application
                    );
                }
            }

            //Apply limit and offset
            if($this->_state->limit) {
                $data = array_slice($data, $this->_state->offset, $this->_state->limit);
            }

            $this->_list = KFactory::tmp('admin::com.templates.database.rowset.templates', array('data' => $data));
        }

        return $this->_list;
    }

    /**
     * Get the total amount of items
     *
     * @return  int
     */
    public function getTotal()
    {
        if(!isset($this->_total))
        {
            $this->_total = count($this->getList());
        }

        return $this->_total;
    }
}