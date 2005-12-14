<h1>##FILESHARE_TITLE##</h1>
<h3>##FILEDETAILS## : {$myFile->getName()}</h3>
<div class="fileshare">
	<div class="filedetails{if ($myFile->getExtension() != "")} {$myFile->getExtension()}{/if}">
		<div class="detail name">
			<label for="name">##NAME## :</label> 
			<span id="name">
				{$myFile->getName()}&nbsp;
			</span>
		</div>
		<div class="detail description">
			<label for="description">##DESCRIPTION## :</label>
			<span id="description">
				{$myFile->getLastVersionInfo("description")}&nbsp;
			</span>
		</div>
		<div class="detail size">
			<label for="size">##SIZE## : </label>
			<span id="size">
			{assign var="filesize" value=$myFile->getSize()}
			{if $filesize > 1024*1000}
				{$myFile->getSize()/1024/1000|@round:2}MB
			{else}
				{$myFile->getSize()/1024|@round:1}KB
			{/if}&nbsp;
			</span>
		</div>
		<div class="detail creator">
			<label for="description">##CREATOR## :</label>
			<span id="description">
				{$myFile->getLastVersionInfo("user")}&nbsp;
			</span>
		</div>
		<div class="detail date">
			<label for="date">##UPLOAD_DATE## :</label>
			<span id="date">
				{$myFile->getSysInfos("datetime")}&nbsp;
			</span>
		</div>
		<div class="downloadlink">
			<a href="{kurl page="download" filename=$myFile->getPathBase64()}" title="##DOWNLOAD## {$myFile->getName()}">##DOWNLOAD## {$myFile->getName()}</a>
		</div>

		{assign var="versions" value=$myFile->getAllVersions()}
		{if $versions|@count>0}
		<div class="versions">
			<label for="versions">##VERSIONS## :</label>
			<ul id="versions">
			{foreach from=$versions item=version}
				<li>
					<div class="date">
						<label for="date_v{$version.vid}">
						##DATE## :
						</label>
						<span id="date_v{$version.vid}">
						{$version.datetime}
						</span>
					</div>
					<div class="user">
						<label for="user_v{$version.vid}">
						##CREATOR## :
						</label>
						<span id="user_v{$version.vid}">
						{$version.user}
						</span>
					</div>
					<div class="description">
						<label for="description_v{$version.vid}">
						##DESCRIPTION## :
						</label>
						<span id="description_v{$version.vid}">
						{$version.description}
						</span>
					</div>
				</li>
			{/foreach}			
			</ul>
		</div>
		{/if}
		<a href="{kurl page="addversion" fileid=$myFile->getFileId()}">##ADD_NEW_VERSION##</a>
	</div>
</div>