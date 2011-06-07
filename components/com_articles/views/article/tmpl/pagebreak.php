<?php
/**
 * @version     $Id: form.php 1638 2011-06-07 23:00:45Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

		<script type="text/javascript">
			function insertPagebreak(editor)
			{
				// Get the pagebreak title
				var title = document.getElementById("title").value;
				if (title != '') {
					title = "title=\""+title+"\" ";
				}

				// Get the pagebreak toc alias -- not inserting for now
				// don't know which attribute to use...
				var alt = document.getElementById("alt").value;
				if (alt != '') {
					alt = "alt=\""+alt+"\" ";
				}

				var tag = "<hr class=\"system-pagebreak\" "+title+" "+alt+"/>";

				window.parent.jInsertEditorText(tag, '<?php echo preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', JRequest::getVar('e_name') ); ?>');
				window.parent.document.getElementById('sbox-window').close();
				return false;
			}
		</script>

		<form>
		<table width="100%" align="center">
			<tr width="40%">
				<td class="key" align="right">
					<label for="title">
						<?php echo JText::_( 'PGB PAGE TITLE' ); ?>
					</label>
				</td>
				<td>
					<input type="text" id="title" name="title" />
				</td>
			</tr>
			<tr width="60%">
				<td class="key" align="right">
					<label for="alias">
						<?php echo JText::_( 'PGB TOC ALIAS PROMPT' ); ?>
					</label>
				</td>
				<td>
					<input type="text" id="alt" name="alt" />
				</td>
			</tr>
		</table>
		</form>
		<button onclick="insertPagebreak();"><?php echo JText::_( 'PGB INS PAGEBRK' ); ?></button>
