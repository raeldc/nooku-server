<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Nodes Model Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesModelNodes extends ComFilesModelDefault
{
	public function getList()
	{
		if (!isset($this->_list))
		{
			$state = $this->_state;
			$type = !empty($state->types) ? (array) $state->types : array();

			$list = $this->getService('com://admin/files.database.rowset.nodes');

			// Special case for limit=0. We set it to -1
			// so loop goes on till end since limit is a negative value
			$limit_left = $state->limit ? $state->limit : -1;
			$offset_left = $state->offset;
			$total = 0;

			if (empty($type) || in_array('folder', $type))
			{
				$folders = $this->getService('com://admin/files.model.folders')->set($state->getData())->getList();
					
				foreach ($folders as $folder) 
				{
					if (!$limit_left) {
						break;
					}
					if ($offset_left) {
						$offset_left--;
						continue;
					}
					$list->insert($folder);
					$limit_left--;
				}

				$total += count($folders);
			}


			if ((empty($type) || (in_array('file', $type) || in_array('image', $type))))
			{
				$files = $this->getService('com://admin/files.model.files')->set($state->getData())->getList();

				foreach ($files as $file) 
				{
					if (!$limit_left) {
						break;
					}
					if ($offset_left) {
						$offset_left--;
						continue;
					}
					$list->insert($file);
					$limit_left--;
				}

				$total += count($files);
			}

			$this->_total = $total;

			//$list = array_slice($list, $state->offset, $state->limit ? $state->limit : $this->_total);

			$this->_list = $list;
		}

		return parent::getList();
	}

	public function getColumn($column)
	{
		return $this->getList();
	}
}
