	{if ($fileEntries|@count)>0}
		<ul>
			{foreach from=$fileEntries key=key item=entry}
			<li>
				{if $entry->isFolder()}
				<a href="#" onclick="changeFolder('{$entry->getFullPath()}')">
				<img src="themes/karibou/images/fileshare/detailed/directory.png" />{$entry->getName()}
				</a>
				{else}
				<a href="{kurl app="myfiles" page="download"}?fileName={$entry->getFullPath()}">
				<img src="themes/karibou/images/fileshare/detailed/default.png" />{$entry->getName()}
				</a>
				{/if}
				{if !$entry->isFolder()}
					{$entry->getSize()} bytes
				{/if}
			</li>
			{/foreach}
		</ul>
	{/if}