<h1>##FILESHARE_TITLE##</h1>
<h3>{if $myElement->isFile()}##FILEDETAILS##{else}##DIRECTORYDETAILS##{/if} : {$myElement->getName()}</h3>

{if $myElement->isFile() && $myElement->existsInDB()}
	{assign var="versions" value=$myElement->getAllVersions()}
{/if}

<div class="fileshare">
	<ul class="detailed directory">
		<li>
			<a href="{kurl page="directory" directoryname=$myElement->getParentPathBase64()}">
				<span class="name">##UPONELEVEL##</span>
				<span class="name">{$myElement->getParentPath()}</span>
			</a>
		</li>
	</ul>

	{if !$myElement->existsInDB()}
		<div class="helper">##DOESNOTEXISTINDB##</div>
	{elseif $myElement->isFile()}
		<div class="helper">##FILEDETAILS_DESCRIPTION##</div>
	{elseif $myElement->isDirectory()}
		<div class="helper">##DIRECTORYDETAILS_DESCRIPTION##</div>
	{/if}
	
	<div class="details{if ($myElement->isFile() && ($myElement->getExtension() != ""))} {$myElement->getExtension()|lower}{elseif $myElement->isDirectory()} directory{/if}">
		<div class="detail name">
			<label for="name">##NAME## :</label> 
			<span id="name">
				{$myElement->getName()}&nbsp;
			</span>
		</div>
		{if $myElement->existsInDB()}
		<div class="detail description">
			<label for="description">##DESCRIPTION## :</label>
			<span id="description">
				{$myElement->getLastVersionInfo("description")}&nbsp;
			</span>
		</div>
			{if $myElement->isFile()}
		<div class="detail version">
			<label for="version">##VERSION## :</label>
			<span id="version">
				{$myElement->getLastVersionInfo("versionid")}&nbsp;
			</span>
		</div>
			{/if}
		{/if}
		
		{if $myElement->isFile()}
		<div class="detail size">
			<label for="size">##SIZE## : </label>
			<span id="size">
			{assign var="filesize" value=$myElement->getSize()}
			{if $filesize > 1024*1000}
				{$filesize/1024/1000|@round:2}MB
			{else}
				{$filesize/1024|@round:1}KB
			{/if}&nbsp;
			</span>
		</div>
		{/if}
		
		{if $myElement->existsInDB()}
			{if $myElement->isFile()}
		<div class="detail hits">
			<label for="hits">##DOWNLOAD_COUNT## :</label>
			<span id="hits">
				{if $myElement->getHitsByVersion() != NULL}
					{$myElement->getHitsByVersion()}
				{else}
					0
				{/if}&nbsp;
			</span>
		</div>
			{/if}
		<div class="detail uploader">
			<label for="uploader">##UPLOADER## :</label>
			<span id="uploader">
				{assign var="uploader" value=$myElement->getLastVersionInfo("user")}
				<a href="{kurl app="annuaire" username=$uploader->getLogin()}">{$uploader->getUserLink()}</a>&nbsp;
			</span>
		</div>
		<div class="detail creator">
			<label for="creator">##CREATOR## :</label>
			<span id="creator">
				<a href="{kurl app="annuaire" username=$myElement->creator->getLogin()}">{$myElement->creator->getUserLink()}</a>&nbsp;
			</span>
		</div>
			{if $myElement->getSysInfos("groupowner") != NULL}
		<div class="detail groupowner">
			<label for="groupowner">##FILEDETAILS_GROUPOWNER## :</label>
			<span id="groupowner">
				{$myElement->getSysInfos("groupowner")}&nbsp;
			</span>
		</div>
			{/if}
		{/if}
		
		{if $versions|@count>0}
		<div class="detail uploadname">
			<label for="uploadname">
			##UPLOADNAME## :
			</label>
			<span id="uploadname">
			{$myElement->getLastVersionInfo("uploadname")}&nbsp;
			</span>
		</div>
		{/if}
		
		<div class="detail date">
			<label for="date">##UPLOADDATE## :</label>
			<span id="date">
				{$myElement->getModificationDate()|date_format:"%d %B %Y @ %H:%M"}&nbsp;
			</span>
		</div>
		
		{if $myElement->isFile()}
		<div class="downloadlink">
			<a href="{kurl page="download" filename=$myElement->getPathBase64()}" title="##DOWNLOAD## {$myElement->getName()}">##DOWNLOAD## {$myElement->getName()}</a>
		</div>
		{/if}
		
		{if ($myElement->existsInDB() && $myElement->canWrite())}
		<div class="toolbox">
			{if $myElement->isFile()}
			<div class="movelink">
				<a href="{kurl page="movewhere" elementid=$myElement->getElementId()}">##MOVE_FILE##</a>
			</div>
			{/if}
			{if $myElement->isFile() || ($myElement->isDirectory() && $myElement->isEmpty())}
			<form method="post" action="{kurl page="deletefile"}" id="deletefile" name="deletefile">
				<input type="hidden" name="fileid" value="{$myElement->getElementId()}">
				<a href="#" onclick="if(confirm('##DELETE_ASKIFSURE## {$myElement->getName()} ?')){ldelim}document.deletefile.submit();return false;{rdelim}else{ldelim}return false;{rdelim};">##DELETE## {if $myElement->isFile()}##THIS_FILE##{else}##THIS_DIRECTORY##{/if}</a>
			</form>
			{/if}
			{if $myElement->isFile()}
			<div class="renamelink">
				<a href="{kurl page="renameform" elementid=$myElement->getElementId()}">##FS_RENAME_FILE##</a>
			</div>
			{else if ($myElement->isDirectory())}
			<div class="renamelink">
				<a href="{kurl page="renameform" elementid=$myElement->getElementId()}">##FS_RENAME_DIRECTORY##</a>
			</div>
			{/if}
		</div>
		{/if}
		
	{if $myElement->isFile() && $myElement->existsInDB()}
		{if $versions|@count>0}
		<div class="versions">
			<label for="versions" class="title">##VERSIONS## :</label>
			<ul id="versions">
			{foreach from=$versions item=version}
				{if $myElement->getLastVersionInfo("versionid") != $version->getInfo("versionid")}
				<li class="{cycle values="one,two"}">
					<div class="detail versionid">
						<label for="versionid_v{$version->getInfo("versionid")}">
						##VERSION## :
						</label>
						<span id="versionid_v{$version->getInfo("versionid")}">
						{if $version->getInfo("versionid") != 1}
							{$version->getInfo("versionid")}
						{else}
							##ORIGINAL##
						{/if}&nbsp;
						</span>
					</div>
					<div class="detail date">
						<label for="date_v{$version->getInfo("versionid")}">
						##UPLOADDATE## :
						</label>
						<span id="date_v{$version->getInfo("versionid")}">
						{$version->getInfo("datetime")|date_format:"%d %B %Y @ %H:%M"}&nbsp;
						</span>
					</div>
					<div class="detail uploader">
						<label for="uploader_v{$version->getInfo("versionid")}">
						##UPLOADER## :
						</label>
						<span id="uploader_v{$version->getInfo("versionid")}">
							{assign var="uploader" value=$version->getInfo("user")}
							<a href="{kurl app="annuaire" username=$uploader->getLogin()}">{$uploader->getUserLink()}</a>&nbsp;
						</span>
					</div>
					{if $version->getInfo("description") != ""}
					<div class="detail description">
						<label for="description_v{$version->getInfo("versionid")}">
						##DESCRIPTION## :
						</label>
						<span id="description_v{$version->getInfo("versionid")}">
						{$version->getInfo("description")}&nbsp;
						</span>
					</div>
					{/if}
					<div class="detail size">
						<label for="size_v{$version->getInfo("versionid")}">##SIZE## : </label>
						<span id="size_v{$version->getInfo("versionid")}">
						{assign var="versionsize" value=$version->getSize()}
						{if $versionsize > 1024*1000}
							{$versionsize/1024/1000|@round:2}MB
						{else}
							{$versionsize/1024|@round:1}KB
						{/if}&nbsp;
						</span>
					</div>
					<div class="detail hits">
						<label for="hits">##DOWNLOAD_COUNT## :</label>
						<span id="hits">
							{if $myElement->getHitsByVersion($version->getInfo("versionid")) != NULL}
								{$myElement->getHitsByVersion($version->getInfo("versionid"))}
							{else}
								0
							{/if}&nbsp;
						</span>
					</div>
					<div class="detail uploadname">
						<label for="uploadname_v{$version->getInfo("versionid")}">
						##UPLOADNAME## :
						</label>
						<span id="uploadname_v{$version->getInfo("versionid")}">
						{$version->getInfo("uploadname")}&nbsp;
						</span>
					</div>
					<div class="downloadlink">
						<a href="{kurl page="downloadversion" fileid=$myElement->getElementId() versionid=$version->getInfo("versionid")}">
						{if ($version->getInfo("versionid") == 1)}
							##DOWNLOADORIGINALVERSIONOF##
						{else}
							##DOWNLOAD## ##VERSION## {$version->getInfo("versionid")} ##FILEDETAILS_OF##
						{/if}
						{$myElement->getName()}</a>
					</div>
				</li>
				{/if}
			{/foreach}			
			</ul>
			{if $myElement->canUpdate()}
			<a href="{kurl page="addversion" fileid=$myElement->getFileId()}" class="add">##ADD_NEW_VERSION##</a>
			{/if}
		</div>
		{/if}
	{/if}
	</div>

</div>