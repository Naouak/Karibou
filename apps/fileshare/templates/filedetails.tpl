<h1>##FILESHARE_TITLE##</h1>
<h3>##FILEDETAILS## : {$myFile->getName()}</h3>

{if $myFile->existsInDB()}
	{assign var="versions" value=$myFile->getAllVersions()}
{/if}

<div class="fileshare">
	<ul class="detailed directory">
		<li>
			<a href="{kurl page="directory" directoryname=$myFile->getParentPathBase64()}">
				<span class="name">##UPONELEVEL##</span>
				<span class="name">/{$myFile->getParentPath()}</span>
			</a>
		</li>
	</ul>

	{if !$myFile->existsInDB()}
		<div class="helper">##DOESNOTEXISTINDB##</div>
	{/if}
	
	<div class="filedetails{if ($myFile->getExtension() != "")} {$myFile->getExtension()}{/if}">
		<div class="toolbox">
			<form method="post" action="{kurl page="savefile"}">
				<input type="hidden" name="fileid" value="{$myFile->getElementId()}">
				<input type="submit" name="delete" value="##DELETE##">
			</form>
		</div>
		<div class="detail name">
			<label for="name">##NAME## :</label> 
			<span id="name">
				{$myFile->getName()}&nbsp;
			</span>
		</div>
		{if $myFile->existsInDB()}
		<div class="detail description">
			<label for="description">##DESCRIPTION## :</label>
			<span id="description">
				{$myFile->getLastVersionInfo("description")}&nbsp;
			</span>
		</div>
		
		<div class="detail version">
			<label for="version">##VERSION## :</label>
			<span id="version">
				{$myFile->getLastVersionInfo("versionid")}&nbsp;
			</span>
		</div>
		{/if}
		<div class="detail size">
			<label for="size">##SIZE## : </label>
			<span id="size">
			{assign var="filesize" value=$myFile->getSize()}
			{if $filesize > 1024*1000}
				{$filesize/1024/1000|@round:2}MB
			{else}
				{$filesize/1024|@round:1}KB
			{/if}&nbsp;
			</span>
		</div>
		{if $myFile->existsInDB()}
		<div class="detail uploader">
			<label for="uploader">##UPLOADER## :</label>
			<span id="uploader">
				{assign var="uploader" value=$myFile->getLastVersionInfo("user")}
				<a href="{kurl app="annuaire" username=$uploader->getLogin()}">{$uploader->getUserLink()}</a>&nbsp;
			</span>
		</div>
		<div class="detail creator">
			<label for="creator">##CREATOR## :</label>
			<span id="creator">
				<a href="{kurl app="annuaire" username=$myFile->creator->getLogin()}">{$myFile->creator->getUserLink()}</a>&nbsp;
			</span>
		</div>
			{if $myFile->getSysInfos("groupowner") != NULL}
		<div class="detail groupowner">
			<label for="groupowner">##GROUPOWNER## :</label>
			<span id="groupowner">
				{$myFile->getSysInfos("groupowner")}&nbsp;
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
			{$myFile->getLastVersionInfo("uploadname")}&nbsp;
			</span>
		</div>
		{/if}
		<div class="detail date">
			<label for="date">##UPLOADDATE## :</label>
			<span id="date">
				{$myFile->getModificationDate()|date_format:"%d %B %Y @ %H:%M"}&nbsp;
			</span>
		</div>
		<div class="downloadlink">
			<a href="{kurl page="download" filename=$myFile->getPathBase64()}" title="##DOWNLOAD## {$myFile->getName()}">##DOWNLOAD## {$myFile->getName()}</a>
		</div>
		
	{if $myFile->existsInDB()}
		{if $versions|@count>0}
		<div class="versions">
			<label for="versions" class="title">##VERSIONS## :</label>
			<ul id="versions">
			{foreach from=$versions item=version}
{if $myFile->getLastVersionInfo("versionid") != $version->getInfo("versionid")}
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
						{/if}
						</span>
					</div>
					<div class="detail date">
						<label for="date_v{$version->getInfo("versionid")}">
						##UPLOADDATE## :
						</label>
						<span id="date_v{$version->getInfo("versionid")}">
						{$version->getInfo("datetime")|date_format:"%d %B %Y @ %H:%M"}
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
					<div class="detail uploadname">
						<label for="uploadname_v{$version->getInfo("versionid")}">
						##UPLOADNAME## :
						</label>
						<span id="uploadname_v{$version->getInfo("versionid")}">
						{$version->getInfo("uploadname")}&nbsp;
						</span>
					</div>
					<div class="downloadlink">
						<a href="{kurl page="downloadversion" fileid=$myFile->getElementId() versionid=$version->getInfo("versionid")}">
						{if ($version->getInfo("versionid") == 1)}
							##DOWNLOADORIGINALVERSIONOF##
						{else}
							##DOWNLOAD## ##VERSION## {$version->getInfo("versionid")} ##OF##
						{/if}
						{$myFile->getName()}</a>
					</div>
				</li>
{/if}
			{/foreach}			
			</ul>
		</div>
		{/if}
		<a href="{kurl page="addversion" fileid=$myFile->getFileId()}">##ADD_NEW_VERSION##</a>
	{/if}
	</div>

</div>