{foreach from=$page->getNavContentArray() item=navSection name=navSection}
{if !$admin || $smarty.foreach.navSection.index > 0}
			<ul class="sideNav">
{if is_array($navSection)}
{foreach key=navItem item=navLink from=$navSection}
				<li><a href="{$navLink}">{$navItem}</a></li>
{/foreach}
{else}{$navSection}
{/if}
			</ul>
{/if}
{/foreach}