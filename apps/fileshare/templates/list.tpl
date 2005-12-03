{assign var=subdirs value=$myDir->returnSubDirList()}
<ul>
{if !$myDir->isRootDir()}
	<li class="directory">
		<a href="{kurl page="directory" directoryname=$myDir->getParentPathBase64()}">
			<div class="name">##UPONELEVEL##</div>
		</a>
	</li>
{/if}
{if $subdirs|@count > 0}
{foreach item=directory from=$subdirs}
	<li class="directory">
		<a href="{kurl page="directory" directoryname=$directory->getPathBase64()}" title="{$directory->getName()}">
			<div class="name">{$directory->getName()|truncate:18:"[...]":true}</div>
			<div class="date">{$directory->getModificationDate()|date_format:"%d %B %Y @ %H:%M"}</div>
		</a>
	</li>
{/foreach}
{/if}
</ul>

{assign var=files value=$myDir->returnFileList()}
{if $files|@count>0}
<ul>
{foreach item=file from=$files}
	<li class="file{if ($file->getExtension() != "")} {$file->getExtension()}{/if}">
		<a href="{kurl page="download" filename=$file->getPathBase64()}" title="{$file->getName()}">
			<div class="name">{$file->getShortName()|truncate:18:"[...]":true}{if ($file->getExtension() != "")}.{$file->getExtension()|truncate:7:"":true}{/if}</div>
			<div class="size">
				{assign var="filesize" value=$file->getSize()}
				{if $filesize > 1024*1000}
					{$file->getSize()/1024/1000|@round:2}MB
				{else}
					{$file->getSize()/1024|@round:1}KB
				{/if}
			</div>
			<div class="date">{$file->getModificationDate()|date_format:"%d %B %Y @ %H:%M"}</div>
		</a>
	</li>
{/foreach}
</ul>
{else}
{/if}

{if $subdirs|@count == 0 && $files|@count == 0}
##DIRECTORYEMPTY##
{/if}