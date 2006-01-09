{assign var=subdirs value=$myDir->returnSubDirList()}
{assign var=files value=$myDir->returnFileList()}

{if ($viewtype == 'largeicons')}
	{if $subdirs|@count == 0 && $files|@count == 0}
	<li>
		<strong>##DIRECTORYEMPTY##</strong>
	</li>
	{/if}
	<ul class="largeicons directory">
	{if !$myDir->isRootDir()}
		<li>
			<a href="{kurl page="directory" directoryname=$myDir->getParentPathBase64()}">
				<span class="name">##UPONELEVEL##</span>
			</a>
		</li>
	{/if}
	
	{if $subdirs|@count > 0}
		{foreach item=directory from=$subdirs}
		<li>
			<a href="{kurl page="directory" directoryname=$directory->getPathBase64()}" title="{$directory->getName()}">
				<span class="name">{$directory->getName()|truncate:18:"[...]":true}</span>
				<span class="date">{$directory->getModificationDate()|date_format:"%d %B %Y @ %H:%M"}</span>
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
				<span class="name">{$file->getShortName()|truncate:18:"[...]":true}{if ($file->getExtension() != "")}.{$file->getExtension()|truncate:7:"":true}{/if}</span>
				<span class="size">
					{assign var="filesize" value=$file->getSize()}
					{if $filesize > 1024*1000}
						{$file->getSize()/1024/1000|@round:2}MB
					{else}
						{$file->getSize()/1024|@round:1}KB
					{/if}
				</span>
				<span class="date">{$file->getModificationDate()|date_format:"%d %B %Y @ %H:%M"}</span>
				
			</a>
		</li>
		{/foreach}
	</ul>
	{else}
	{/if}
{elseif ($viewtype == 'detailed')}
	<ul class="detailed header">
		<li>
			<span class="name">##NAME##</span>
			<span class="size">##SIZE##</span>
			<span class="date">##MODIFICATION_DATE##</span>
			<span class="description">##DESCRIPTION##</span>
			<span class="versionid">##VERSIONID##</span>
			{*<span class="rights">##YOURRIGHTS##</span>*}
		</li>
	</ul>
	<ul class="detailed directory">
	{if !$myDir->isRootDir()}
		<li>
			<a href="{kurl page="directory" directoryname=$myDir->getParentPathBase64()}">
				<span class="name">##UPONELEVEL##</span>
			</a>
		</li>
	{/if}

	{if $subdirs|@count > 0}
		{foreach item=directory from=$subdirs}
		<li>
			{if !$directory->existsInDb()}<span class="unknown" title="##UNKNOWNINDB##"><span>&nbsp;</span></span>{/if}
			<a href="{kurl page="directory" directoryname=$directory->getPathBase64()}">
				<span class="name" title="{$directory->getName()}">{$directory->getName()}</span>
				<span class="date">{$directory->getModificationDate()|date_format:"%d/%m/%Y %H:%M"}</span>
				<span class="description">{$directory->getLastVersionInfo("description")|wordwrap:25:" ":true|escape:"html"}&nbsp;</span>
				<span class="versionid">{if $directory->getLastVersionInfo("versionid") > 1}{$directory->getLastVersionInfo("versionid")}{else}&nbsp;{/if}</span>
				{*<span class="rights">?</span>*}
			</a>
			<a href="{kurl page="details" elementpath=$directory->getPathBase64()}" title="##VIEW_DIRECTORY## {$directory->getName()}">
				<span class="detailslink"><span>##VIEW##</span></span>
			</a>
		</li>
		{/foreach}
	{/if}
	</ul>
	
	
	{if $files|@count>0}
	<ul class="detailed file">
		{foreach item=file from=$files}
		<li class="{if ($file->getExtension() != "")}{$file->getExtension()}{/if}">
			{if !$file->existsInDb()}<span class="unknown" title="##UNKNOWNINDB##"><span>&nbsp;</span></span>{/if}
			<a href="{kurl page="details" elementpath=$file->getPathBase64()}" title="{$file->getName()}">
				<span class="name" title="{$file->getName()}">{$file->getShortName()}{if ($file->getExtension() != "")}.{$file->getExtension()|truncate:7:"":true}{/if}</span>
				<span class="size">
					{assign var="filesize" value=$file->getSize()}
					{if $filesize > 1024*1000}
						{$file->getSize()/1024/1000|@round:2}MB
					{else}
						{$file->getSize()/1024|@round:1}KB
					{/if}
				</span>
				<span class="date">{$file->getModificationDate()|date_format:"%d/%m/%Y %H:%M"}</span>
				<span class="description">{$file->getLastVersionInfo("description")|wordwrap:25:" ":true|escape:"html"}&nbsp;</span>
				<span class="versionid">{if $file->getLastVersionInfo("versionid") > 1}{$file->getLastVersionInfo("versionid")}{else}&nbsp;{/if}</span>
				{*<span class="rights">?</span>*}
			</a>
			<a href="{kurl page="download" filename=$file->getPathBase64()}" title="##DOWNLOAD## {$file->getName()}">
				<span class="downloadlink"><span>##DOWNLOAD##</span></span>
			</a>
		</li>
		{/foreach}
	</ul>
	{else}
	{/if}
	{if $subdirs|@count == 0 && $files|@count == 0}
		<div class="detailed empty">##DIRECTORYEMPTY##</div>
	{/if}
{/if}