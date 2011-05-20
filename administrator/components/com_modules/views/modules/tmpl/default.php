<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<script src="media://lib_koowa/js/koowa.js" />

<?= @helper('behavior.tooltip') ?>
<?= @helper('behavior.modal') ?>
<script>
    window.addEvent('domready', function(){
        var workaround = document.getElement('#toolbar-modules-new a');
        workaround.href += '&application=<?= $state->application ?>';
    });
</script>

<div id="sidebar">
   	<h3><?= @text( 'Applications' ); ?></h3>
   	<ul>
        <li <? if($state->application == 'site') echo 'class="active"' ?>>
        	<a href="<?= @route('&application=site') ?>">
        	    <?= @text('Site') ?>
        	</a>
        </li>
        <li <? if($state->application == 'admin') echo 'class="active"' ?>>
        	<a href="<?= @route('&application=admin') ?>">
        	    <?= @text('Administrator') ?>
        	</a>
        </li>
    </ul>
</div>
<div class="-koowa-box-flex">
    <form action="<?= @route() ?>" method="get" name="adminForm" class="-koowa-grid">
    	<table class="adminlist">
    		<thead>
    			<tr>
    				<th width="20"></th>
    				<th class="title">
    					<?= @helper('grid.sort', array('column' => 'title' , 'title' => 'Name')) ?>
    				</th>
    				<th nowrap="nowrap" width="7%">
    					<?= @helper('grid.sort', array('column' => 'enabled' , 'title' => 'Enabled')) ?>
    				</th>
    				<th width="80" nowrap="nowrap">
    					<?= @helper('grid.sort', array('column' => 'ordering' , 'title' => 'Order')) ?>
    				</th>
    				<? if($state->application == 'site') : ?>
    					<th nowrap="nowrap" width="7%">
    						<?= @helper('grid.sort', array('column' => 'groupname' , 'title' => 'Access')) ?>
    					</th>
    				<? endif ?>
    				<th nowrap="nowrap" width="7%">
    					<?= @helper('grid.sort', array('column' => 'position' , 'title' => 'Position')) ?>
    				</th>
    				<th nowrap="nowrap" width="5%">
    					<?= @helper('grid.sort', array('column' => 'pages' , 'title' => 'Pages')) ?>
    				</th>
    				<th nowrap="nowrap" width="10%"  class="title">
    					<?= @helper('grid.sort', array('column' => 'module' , 'title' => 'Type')) ?>
    				</th>
    			</tr>
    			<tr>
    				<td width="5" align="center">
    					<?= @helper( 'grid.checkall'); ?>
    				</td>
    				<td>
    					<?= @text( 'Filter' ) ?>:
    					<?= @template('admin::com.default.view.list.search_form') ?>
    				</td>
    				<td align="center">
    					<?= @helper('listbox.enabled') ?>
    				</td>
    				<td></td>
    				<? if($state->application == 'site') : ?>
    					<td></td>
    				<? endif ?>
    				<td align="center">
    					<?= @helper('listbox.positions') ?>
    				</td>
    				<td></td>
    				<td>
    					<?= @helper('listbox.module') ?>
    				</td>
    			</tr>
    		</thead>
    		<tfoot>
    			<? if ($modules) : ?>
    			<tr>
    				<td colspan="20">
    					<?= @helper('paginator.pagination', array('total' => $total)) ?>
    				</td>
    			</tr>
    			<? endif ?>
    		</tfoot>
    		<tbody>
    		<? foreach ($modules as $module) : ?>
    			<tr>
    				<td width="20" align="center">
    					<?= @helper('grid.checkbox',array('row' => $module)) ?>
    				</td>
    				<td class="title">
    					<a href="<?= @route('view=module&id='.$module->id.'&application='.$state->application) ?>">
    					    <?= @escape($module->title) ?>
    					</a>
    				</td>
    				<td align="center" width="15px">
    					<?= @helper('grid.enable', array('row' => $module)) ?>
    				</td>
    				<td class="order">
    					<?= @helper('grid.order', array('row'=> $module))?>
    				</td>
    				<? if($state->application == 'site') : ?>
    					<td align="center">
    						<?= @helper('grid.access', array('row' => $module)) ?>
    					</td>
    				<? endif ?>
    				<td align="center">
    					<?= $module->position ?>
    				</td>
    				<td align="center">
    					<?= @text(
    						is_array($module->pages) ? 'Varies' : $module->pages
    					) ?>
    				</td>
    				<td>
    					<?= $module->module ? $module->module : @text( 'User' ) ?>
    				</td>
    			</tr>
    		<? endforeach ?>
    		</tbody>
    	</table>
    </form>
</div>