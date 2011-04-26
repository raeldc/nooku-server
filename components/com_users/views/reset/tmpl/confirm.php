<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<div class="componentheading">
    <?= @('Confirm your Account') ?>
</div>

<form action="<?= @route() ?>" method="post" class="josForm form-validate">
    <input type="hidden" name="action" value="confirm" />

    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
        <tr>
            <td colspan="2" height="40">
                <p><?= @text('RESET_PASSWORD_CONFIRM_DESCRIPTION') ?></p>
            </td>
        </tr>
        <tr>
            <td height="40">
                <label for="username" class="hasTip" title="<?= @text('RESET_PASSWORD_USERNAME_TIP_TITLE') ?>::<?= @text('RESET_PASSWORD_USERNAME_TIP_TEXT') ?>">
                    <?= @text('User Name') ?>:
                </label>
            </td>
            <td>
                <input id="username" name="username" type="text" class="required" size="36" />
            </td>
        </tr>
        <tr>
            <td height="40">
                <label for="token" class="hasTip" title="<?= @text('RESET_PASSWORD_TOKEN_TIP_TITLE') ?>::<?= @text('RESET_PASSWORD_TOKEN_TIP_TEXT') ?>">
                    <?= @text('Token') ?>:
                </label>
            </td>
            <td>
                <input id="token" name="token" type="text" class="required" size="36" />
            </td>
        </tr>
    </table>

    <button type="submit" class="validate"><?= @text('Submit') ?></button>
</form>