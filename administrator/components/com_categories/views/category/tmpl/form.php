<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('behavior.validator') ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<form action="<?= @route('id='.$category->id) ?>" method="post" class="-koowa-form" id="category-form">
	<input type="hidden" name="section" value="<?= $category->id? $category->section : $state->section; ?>" />
	
    <div class="grid_8">
 		<div class="border-radius-4 title clearfix">
			<input class="inputbox border-radius-4 required" type="text" name="title" id="title" size="40" maxlength="255" value="<?= $category->title; ?>" placeholder="<?= @text( 'Title' ); ?>" />
            <label for="alias">
                <?= @text( 'Alias' ); ?>
                <input class="inputbox border-radius-4" type="text" name="alias" id="alias" size="40" maxlength="255" value="<?= $category->slug; ?>" title="<?= @text( 'ALIASTIP' ); ?>" placeholder="<?= @text( 'Alias' ); ?>" />
            </label>
        </div>
        <?= @editor( array('name' => 'description',
                            'editor' => 'tinymce',  
                            'width' => '100%', 
                            'height' => '300', 
                            'cols' => '60', 
                            'rows' => '20', 
                            'buttons' => null, 
                            'options' => array('theme' => 'simple', 'pagebreak', 'readmore'))); 
            ?>
    </div>

        <div class="grid_4">
            <div class="panel">
                <h3><?= @text( 'Publish' ); ?></h3>
                <table class="paramlist admintable">
                	<tr>
                    	<td class="key">
                            <?= @text( 'Published' ); ?>:
                    	</td>
                    	<td>
                            <?= @helper('select.booleanlist', array('name' => 'enabled', 'selected' => $category->enabled)) ?>
                    	</td>
                	</tr>
                	<tr>
                    	<td class="key">
                            <label for="section">
                                <?= @text( 'Section' ); ?>:
                            </label>
                    	</td>
                    	<td>
                            <? $section = $category->id ? $category->section : $state->section ;
                            if ( substr($section, 0, 3) == 'com' && $section !='com_content') : ?>
                                <input type="hidden" name="section" value="<?= $section ?>"  />
 	                        <?= @text($section) ; 
                            else : ?>
                                <input type="hidden" name="old_parent" value="<?= $category->section ?>" />
                                <?= @helper('listbox.section', array(
                                    'identifier' => "admin::com.sections.model.sections",
                                    'attribs'    => array(
                                        'id'   => 'section',
                                        'onchange' => "var url = '"
                                        	.@route('&id=&layout=form_orderable&tmpl=component&format=ajax')
                                        	."&section='+$('section').value;
                                                       new Ajax(url , {method: 'get',update: $('orderable')}).request();"
                                    )
                                ));
                            endif  ?>
                    	</td>
                	</tr>
                	<tr>
                    	<td class="key">
                            <label for="ordering">
                                <?= @text( 'Ordering' ); ?>:
                            </label>
                    	</td>
                    	<td>
                    	    <div id="orderable">
								 <? if( $category->id ) : ?>
                                    <?= @helper('listbox.order',
                                    array( 'filter' => array(
                                        'section' => $category->section 
                                    ))); 
                                elseif ( substr($state->section, 0, 3) == 'com' && $state->section != 'com_content'):
                                   echo @template('form_orderable');       
                                endif  ?>
                            </div>
                    	</td>
                	</tr>
                <tr>
                    <td valign="top" class="key">
                        <label for="access">
                            <?= @text( 'Access Level' ); ?>:
                        </label>
                    </td>
                    <td>
                        <?= @helper('listbox.access', array('name' => 'access', 'state' => $category, 'deselect' => false)); ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="panel">
            <h3><?= @text( 'Image' ); ?></h3>
            <table class="paramlist admintable">
                <tr>
                    <td class="key">
                        <label for="image">
                            <?= @text( 'Image' ); ?>:
                        </label>
                    </td>
                    <td>
                        <?= @helper('image.listbox', array('name' => 'image')); ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap="nowrap" class="key">
                        <label for="image_position">
                            <?php echo JText::_( 'Image Position' ); ?>:
                        </label>
                    </td>
                    <td>
                        <?=  @helper('image.position'); ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>