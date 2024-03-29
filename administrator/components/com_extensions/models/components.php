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
 * Components Model Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions    
 */
class ComExtensionsModelComponents extends ComDefaultModelDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
	
		$this->_state
		 	->insert('enabled', 'boolean')
		 	->insert('parent' , 'int')
		 	->insert('component' , 'cmd')
		 	->insert('hidden' , 'boolean');	
	}
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$state = $this->_state;
	
		if($state->search) {
			$query->where('tbl.name', 'LIKE', '%'.$state->search.'%');
		}
		
		if($state->component) {
			$query->where('tbl.option', '=', $state->component);
		}
	
	    if(is_integer($state->parent)) {
			$query->where('tbl.parent', '=', $state->parent);
		}

		if(is_bool($state->enabled)) {
			$query->where('tbl.enabled', '=', (int) $state->enabled);
		}
		
	    if(is_bool($state->hidden)) {
			$query->where('tbl.iscore', '=', (int) $state->hidden);
		}
	
		parent::_buildQueryWhere($query);
	}
}