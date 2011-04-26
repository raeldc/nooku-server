<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Users Model Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */
class ComUsersModelUsers extends KModelTable
{
    /**
     * Constructor.
     *
     * @param   KConfig  An optional KConfig object with configuration options.
     */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_state->insert('group', 'int');
	}

	/**
     * Builds SELECT columns list for the query.
     *
     * @param   KDatabaseQuery  A query object.
     * @return  void
     */
	protected function _buildQueryColumns(KDatabaseQuery $query)
	{
	    parent::_buildQueryColumns($query);

	    $query->select('IF(session.session_id IS NOT NULL, 1, 0) AS logged_in');
	    $query->select('IF(tbl.block = 1, 0, 1) AS enabled');
	}

	/**
     * Builds LEFT JOINS clauses for the query.
     *
     * @param   KDatabaseQuery  A query object.
     * @return  void
     */
	protected function _buildQueryJoins(KDatabaseQuery $query)
	{
	    $query->join('LEFT', 'session AS session', 'tbl.id = session.userid');
	}

	/**
     * Builds a WHERE clause for the query.
     *
     * @param   KDatabaseQuery  A query object.
     * @return  void
     */
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		parent::_buildQueryWhere($query);

		if($this->_state->group) {
			$query->where('users_group_id', '=', $this->_state->group);
		}

	   if($this->_state->search) {
            $query->where('name', 'LIKE', '%'.$this->_state->search.'%')
                ->where('email', 'LIKE', '%'.$this->_state->search.'%', 'OR');
        }
	}
}