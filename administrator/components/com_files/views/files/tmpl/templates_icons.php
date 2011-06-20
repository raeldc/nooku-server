<textarea style="display: none" id="icons_container">
<div>

</div>
</textarea>
<textarea style="display: none" id="icons_parent">
<div class="imgOutline files-node">
	<div class="imgTotal">
		<div align="center" class="imgBorder">
			<a href="#" class="navigate">
				<img src="media://com_files/images/folderup_32.png" width="32" height="32" border="0" alt=".." />
			</a>
		</div>
	</div>
	<div class="controls">
	</div>
	<div class="imginfoBorder ellipsis">
		<a href="#" class="navigate">
			..
		</a>
	</div>
</div>
</textarea>

<textarea style="display: none" id="icons_folder">
<div class="imgOutline files-node files-folder">
	<div class="imgTotal">
		<div align="center" class="imgBorder">
			<a href="#" class="navigate">
				<img src="media://com_files/images/folder.png" width="80" height="80" border="0" />
			</a>
		</div>
	</div>
	<div class="controls">
		<a class="delete-node" href="#" rel="[%=path%]">
			<img src="media://com_files/images/remove.png" width="16" height="16" border="0" alt="<?= @text('Delete'); ?>" />
		</a>
		<input type="checkbox" class="files-select" value="[%=path%]" />
	</div>
	<div class="imginfoBorder ellipsis">
		<a href="#" class="navigate">
			[%=name%]
		</a>
	</div>
</div>
</textarea>

<textarea style="display: none" id="icons_file">
<div class="imgOutline files-node files-file">
	<div class="imgTotal">
		<div align="center" class="imgBorder">
		 	<a class="navigate" href="#" style="display: block; width: 100%; height: 100%">
				<img src="/[%=Files.sitebase%]/[%=icons['32']%]" border="0" />
			</a>
		</div>
	</div>
	<div class="controls">
		<a class="delete-node" href="#" rel="[%=path%]">
			<img src="media://com_files/images/remove.png" width="16" height="16" border="0" alt="<?= @text('Delete'); ?>" />
		</a>
		<input type="checkbox" class="files-select" value="[%=path%]" />
	</div>
	<div class="imginfoBorder ellipsis">
		<a href="#" class="navigate">
			[%=name%]
		</a>
	</div>
</div>
</textarea>

<textarea style="display: none" id="icons_image">
<div class="imgOutline files-node files-image">
	<div class="imgTotal">
		<div align="center" class="imgBorder">
			<a class="img-preview navigate" href="#" title="[%=name%]" style="display: block; width: 100%; height: 100%">
				<div class="image">
					<img src="/[%=baseurl%]/[%=path%]" width="[%=thumbnail.width%]" height="[%=thumbnail.height%]" alt="[%=name%]" border="0" />
				</div>
			</a>
		</div>
	</div>
	<div class="controls">
		<a class="delete-node" href="#" rel="[%=path%]">
			<img src="media://com_files/images/remove.png" width="16" height="16" border="0" alt="<?= @text('Delete'); ?>" />
		</a>
		<input type="checkbox" class="files-select" value="[%=path%]" />
	</div>
	<div class="imginfoBorder ellipsis">
		<a href="#" class="navigate">
			[%=name%]
		</a>
	</div>
</div>
</textarea>