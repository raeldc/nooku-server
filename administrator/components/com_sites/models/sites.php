<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sites
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Sites Model Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sites   
 */
class ComSitesModelSites extends KModelAbstract implements KServiceInstantiatable
{	
     public function __construct(KConfig $config)
     {
         parent::__construct($config);
         
         $this->_state
             ->insert('name'      , 'cmd', null, true)
             ->insert('limit'     , 'int')
             ->insert('offset'    , 'int')
             ->insert('sort'      , 'cmd')
             ->insert('direction' , 'word', 'asc')
             ->insert('search'    , 'string');
    }
    
    public static function getInstance(KConfigInterface $config, KServiceInterface $container)
    { 
       // Check if an instance with this identifier already exists or not
        if (!$container->has($config->service_identifier))
        {
            //Create the singleton
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $container->set($config->service_identifier, $instance);
        }
        
        return $container->get($config->service_identifier);
    }
    
    public function getList()
    { 
        if(!isset($this->_list))
        {
            $data = array();
            
            //Get the sites
			foreach(new DirectoryIterator(JPATH_SITES) as $file)
			{
				if($file->isDir() && !(substr($file->getFilename(), 0, 1) == '.')) 
				{
        			$data[] = array(
        				'name' => $file->getFilename()
				    );
    			}
			}
			
            //Apply state information
            foreach($data as $key => $value)
            {   
                if($this->_state->search)
                {
                     if($value->name != $this->_state->search) {
                         unset($data[$key]);
                      }
                }
            }
                        
            //Set the total
            $this->_total = count($data);
                    
            //Apply limit and offset
            if($this->_state->limit) {
                $data = array_slice($data, $this->_state->offset, $this->_state->limit);
            }
                        
            $this->_list = $this->getService('com://admin/sites.database.rowset.sites', array('data' => $data));
        }
        
        return $this->_list;
    }
    
    public function getTotal()
    {
        if(!isset($this->_total)) {
            $this->getList();
        }
        
        return $this->_total;
    }
}