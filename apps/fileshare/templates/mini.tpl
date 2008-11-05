<h3>##FILESHARE##</h3>
<div class="fileshare mini">
	<div class="lastadded">
		<h4>##LASTADDEDFILES##</h4>
		{if $lastAddedFiles|@count > 0}
		<ul>
			{foreach from=$lastAddedFiles item="file"}
				<li>
					<span class="name">
					
						<a href="{kurl page="details" elementpath=$file->getPathBase64()}" title="{$file->getName()}" class="{if ($file->getExtension() != "")}{$file->getExtension()}{/if}">{$file->getSysInfos("name")} {if $file->getLastVersionInfo("versionid")>1}(v{$file->getLastVersionInfo("versionid")}){/if}</a>
					</span>
					
					<a href="{kurl page="download" filename=$file->getPathBase64()}" title="##DOWNLOAD## {$file->getName()}">
						<span class="downloadlink"><span>##DOWNLOAD##</span></span>
					</a>(<a onClick="new Effect.toggle($('dl_{$file->getFileId()}')); return false;" href="#">infos</a>)
							<div id="dl_{$file->getFileId()}" style="display: none;">
							{if $file->getLastVersionInfo("description") != ""}
							<span class="description">
								<label for="description">##DESCRIPTION## :</label>
								<span name="description">{$file->getLastVersionInfo("description")|strip_tags|truncate:300:"...":false}</span>
							</span>
							{/if}
							<span class="ago">
							##AGO1##
							{assign var="since" value=$file->getSecondsSinceLastUpdate()}
							{if $since > 86400}
								{$since/86400|string_format:"%d"} ##DAY##{if $since/86400|string_format:"%d" >= 2}##S##{/if}
							{elseif $since > 3600}
								{$since/3600|string_format:"%d"} ##HOUR##{if $since/3600|string_format:"%d" >= 2}##S##{/if}
							{elseif $since > 60}
								{$since/60|string_format:"%d"} ##MINUTE##{if $since/60|string_format:"%d" >= 2}##S##{/if}
							{else}
								{$since} ##SECOND##{if $since >= 2}##S##{/if}
							{/if}
							##AGO2##
							</span>
							<span class="uploader">
								<label for="uploader">##UPLOADED_BY## :</label>
								{assign var="uploader" value=$file->getLastVersionInfo("user")}
								<span name="uploader">
									<a href="{kurl app="annuaire" username=$uploader->getLogin()}">{$uploader->getUserLink()}</a>
								</span>
							</span>
							</div>
						
				</li>
			{/foreach}			
			</ul>
		{/if}
	</div>
	<div class="mostdownloaded">
		<h4>##MOSTDOWNLOADEDFILES##</h4>
		{if $mostDownloadedFiles|@count > 0}
		<ul>
			{foreach from=$mostDownloadedFiles item="file"}
				<li>
					<span class="name">
						<a href="{kurl page="details" elementpath=$file->getPathBase64()}" title="{$file->getName()}" class="{if ($file->getExtension() != "")}{$file->getExtension()}{/if}">{$file->getSysInfos("name")} {if $file->getLastVersionInfo("versionid")>1}(v{$file->getLastVersionInfo("versionid")}){/if}</a>
					</span>
					<a href="{kurl page="download" filename=$file->getPathBase64()}" title="##DOWNLOAD## {$file->getName()}">
						<span class="downloadlink"><span>##DOWNLOAD##</span></span>
					</a>(<a onClick="new Effect.toggle($('dl2_{$file->getFileId()}')); return false;" href="#">infos</a>)
					{if $file->getLastVersionInfo("description") != ""}
					<div id="dl2_{$file->getFileId()}" style="display: none;">

					<span class="description">
						<label for="description">##DESCRIPTION## :</label>
						<span name="description">{$file->getLastVersionInfo("description")|strip_tags|truncate:300:"...":false}</span>
					</span>
					{/if}
					<span class="hits">
						<label for="hits">##DOWNLOAD_COUNT## :</label>
						<span name="hits">{$file->getHitsByVersion()}</span>
					</span>
					<span class="uploader">
						<label for="uploader">##UPLOADED_BY## :</label>
						{assign var="uploader" value=$file->getLastVersionInfo("user")}
						<span name="uploader">
							<a href="{kurl app="annuaire" username=$uploader->getLogin()}">{$uploader->getUserLink()}</a>
						</span>
					</span>
				</li>
			{/foreach}
		</ul>
		{/if}
	</div>
</div>