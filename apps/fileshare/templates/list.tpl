{assign var=subdirs value=$myDir->returnSubDirList()}
{assign var=files value=$myDir->returnFileList()}

{if $subdirs|@count == 0 && $files|@count == 0}
<strong>##DIRECTORYEMPTY##</strong>
{/if}

{if ($viewtype == 'largeicons')}
	<ul class="largeicons directory">
	{if !$myDir->isRootDir()}
		<li>
			<a href="{kurl page="directory" directoryname=$myDir->getParentPathBase64()}">
				<div class="name">##UPONELEVEL##</div>
			</a>
		</li>
	{/if}
	
	{if $subdirs|@count > 0}
		{foreach item=directory from=$subdirs}
		<li>
			<a href="{kurl page="directory" directoryname=$directory->getPathBase64()}" title="{$directory->getName()}">
				<div class="name">{$directory->getName()|truncate:18:"[...]":true}</div>
				<div class="date">{$directory->getModificationDate()|date_format:"%d %B %Y @ %H:%M"}</div>
			</a>
		</li>
		{/foreach}
	{/if}
	</ul>
	
	
	{if $files|@count>0}
	<ul class="largeicons file">
		{foreach item=file from=$files}
		<li class="{if ($file->getExtension() != "")}{$file->getExtension()}{/if}">
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
{elseif ($viewtype == 'detailed')}
	<ul class="detailed header">
		<li>
			<div class="name">##NAME##</div>
			<div class="size">##SIZE##</div>
			<div class="date">##MODIFICATION_DATE##</div>
			<div class="description">##DESCRIPTION##</div>
		</li>
	</ul>
	<ul class="detailed directory">
	{if !$myDir->isRootDir()}
		<li>
			<a href="{kurl page="directory" directoryname=$myDir->getParentPathBase64()}">
				<div class="name">##UPONELEVEL##</div>
			</a>
		</li>
	{/if}
	
	{if $subdirs|@count > 0}
		{foreach item=directory from=$subdirs}
		<li>
			<a href="{kurl page="directory" directoryname=$directory->getPathBase64()}" title="{$directory->getName()}">
				<div class="name">{$directory->getName()}</div>
				<div class="date">{$directory->getModificationDate()|date_format:"%d %B %Y @ %H:%M"}</div>
				<div class="description">{$directory->getVersionInfo("description")}</div>
			</a>
		</li>
		{/foreach}
	{/if}
	</ul>
	
	
	{if $files|@count>0}
	<ul class="detailed file">
		{foreach item=file from=$files}
		<li class="{if ($file->getExtension() != "")}{$file->getExtension()}{/if}">
			<a href="{kurl page="download" filename=$file->getPathBase64()}" title="{$file->getName()}">
				<div class="name">{$file->getShortName()}{if ($file->getExtension() != "")}.{$file->getExtension()|truncate:7:"":true}{/if}</div>
				<div class="size">
					{assign var="filesize" value=$file->getSize()}
					{if $filesize > 1024*1000}
						{$file->getSize()/1024/1000|@round:2}MB
					{else}
						{$file->getSize()/1024|@round:1}KB
					{/if}
				</div>
				<div class="date">{$file->getModificationDate()|date_format:"%d %B %Y @ %H:%M"}</div>
				<div class="description">{$file->getVersionInfo("description")}</div>
			</a>
		</li>
		{/foreach}
	</ul>
	{else}
	{/if}
{/if}