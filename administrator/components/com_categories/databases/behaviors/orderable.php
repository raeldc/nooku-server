<?php
/**
 * @version      $Id$
 * @category	  Nooku
 * @package      Nooku_Server
 * @subpackage   Categories
 * @copyright    Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link          http://www.nooku.org
 */

/**
 * Orderable Database Behavior Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	 Nooku
 * @package     Nooku_Server
 * @subpackage  Categories    
 */
class ComCategoriesDatabaseBehaviorOrderable extends KDatabaseBehaviorOrderable
{
    protected $_parent;

    /**
     * The parent column name
     *
     * @var string
     */
    protected $_parent_column;

    
    public function __construct( KConfig $config = null)
    {
        $config->append(array('parent_column' => null));
        $this->_parent_column = $config->parent_column;
        
        parent::__construct($config);
    }

    public function _buildQueryWhere(KDatabaseQuery $query)
    {
        if ($this->_parent_column)
        {
            $parent = $this->_parent ? $this->_parent : $this->{$this->_parent_column};   
            $query->where($this->_parent_column, '=', $parent);
        }
    }
    
    /**
     * Changes the rows ordering if the virtual order field is set. Order is
     * relative to the row's current position. Order is to be only set if section 
     * unchanged.
     * Inserts space in order sequence of new section if section changed.
     *
     * @param   KCommandContext Context
     */
    protected function _beforeTableUpdate(KCommandContext $context)
    {
	    if(isset($this->ordering))
        {
            if (isset($this->order))
            {
                unset($this->old_parent);
                //default action               
                $this->order($this->order);
            } 
            elseif (isset($this->old_parent)) 
            {
                $max = $this->getMaxOrdering();
                if ($this->ordering <= 0){
                    $this->ordering = $max + 1;
                } elseif ($this->ordering <= $max ){
                    //make space for new entry
                    $this->reorder($this->ordering);
                }                
            }
        }
    }

    /**
     * Reorders the old section if record has changed sections
     *
     * @param   KCommandContext Context
     */
    protected function _afterTableUpdate(KCommandContext $context)
    {
        if (isset($this->old_parent) && $this->old_parent != $this->{$this->_parent_column} )
        {
            //section has changed,
            //tidy up the old section
            $this->_parent = $this->old_parent;
            $this->reorder();
        }
    }
}