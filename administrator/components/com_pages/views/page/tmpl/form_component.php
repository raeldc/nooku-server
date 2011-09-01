<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<? $model = KFactory::get('com://admin/pages.model.pages') ?>

<? $component_parameters = $model->getComponentParameters() ?>
<?= @helper('tabs.startPanel', array('title' => 'Component')); ?>
<? if($rendered_parameters = $component_parameters->render('params')) : ?>
<section>
	<fieldset>
		<?= $rendered_parameters ?>
	</fieldset>
</section>
<?= @helper('tabs.endPanel'); ?>
<? endif ?>