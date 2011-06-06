<?php
/**
 * @version     $Id: articles.php 1340 2011-05-18 17:49:12Z gergoerdosi $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Articles Table Model Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */
class ComArticlesModelArticles extends ComDefaultModelDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_state
            ->insert('section'   , 'int', -1)
            ->insert('category'  , 'int', -1)
            ->insert('published' , 'int')
            ->insert('state', 'int')
            ->insert('created_by', 'int')
            ->insert('access', 'int');

        $this->_state->remove('sort')->insert('sort', 'cmd', 'section_title');
    }

    public function getAuthors()
    {
        $query = $this->getTable()->getDatabase()->getQuery();
        $query->select(array('user.id', 'user.name'))
            ->distinct()
            ->order('user.name');

        $this->_buildQueryFrom($query);
        $this->_buildQueryJoins($query);
        $this->_buildQueryWhere($query);

        return $this->getTable()->select($query, KDatabase::FETCH_ROWSET);
    }

    public function getCategories()
    {
        $categories[0][]  = array('0', JText::_('Uncategorised'));

        $list = KFactory::tmp('admin::com.categories.model.categories')
            ->set('section', 'com_content')
            ->set('limit', 0)
            ->set('sort', 'title')
            ->getList();

        foreach($list as $item)
        {
            if(!isset($categories[$item->section])) {
                $categories[$item->section][] = array(-1, '- '.JText::_('Select').' -');
            }

            $categories[$item->section][] = array($item->id, $item->title);
        }

        return $categories;
    }

    protected function _buildQueryColumns(KDatabaseQuery $query)
    {
        parent::_buildQueryColumns($query);

        $query->select('section.title AS section_title')
            ->select('category.title AS category_title')
            ->select('user.name AS created_by_name')
            ->select('IF(frontpage.content_id, 1, 0) AS frontpage')
            ->select('group.name AS group_name');
    }

    protected function _buildQueryJoins(KDatabaseQuery $query)
    {
        $query->join('LEFT', 'sections AS section', 'section.id = tbl.sectionid')
            ->join('LEFT', 'categories AS category', 'category.id = tbl.catid')
            ->join('LEFT', 'users AS user', 'user.id = tbl.created_by')
            ->join('LEFT', 'content_frontpage AS frontpage', 'frontpage.content_id = tbl.id')
            ->join('LEFT', 'groups AS group', 'group.id = tbl.access');
    }

    protected function _buildQueryWhere(KDatabaseQuery $query)
    {
        parent::_buildQueryWhere($query);

        $state = $this->_state;

        if(is_numeric($state->state)) {
            $query->where('tbl.state', '=', $state->state);
        } else {
            $query->where('tbl.state', '<>', -2);
        }

        if($state->search) {
            $query->where('tbl.title', 'LIKE', '%'.$state->search.'%');
        }

        if($state->section > -1) {
            $query->where('tbl.sectionid', '=', $state->section );
        }

        if($state->category > -1) {
            $query->where('tbl.catid', '=',  $state->category);
        }

        if($state->created_by) {
            $query->where('tbl.created_by', '=', $state->created_by);
        }

        if(is_numeric($state->access)) {
            $query->where('tbl.access', '=', $state->access);
        }
    }

    protected function _buildQueryOrder(KDatabaseQuery $query)
    {
        $direction = strtoupper($this->_state->direction);

        if($this->_state->sort == 'ordering')
        {
            $query->order('section_title', 'ASC')
                ->order('category_title', 'ASC')
                ->order('ordering', $direction);
        }
        else
        {
            $query->order($this->_state->sort, $direction)
                ->order('section_title', 'ASC')
                ->order('category_title', 'ASC')
                ->order('ordering', 'ASC');
        }
    }
}