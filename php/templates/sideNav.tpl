{*{foreach from=$page->getNavContentArray() item=navSection name=navSection}*}
{*{if !$admin || $smarty.foreach.navSection.index > 0}*}
			{*<ul class="sideNav">*}
{*{if is_array($navSection)}*}
{*{foreach key=navItem item=navLink from=$navSection}*}
				{*<li><a href="{$navLink}">{$navItem}</a></li>*}
{*{/foreach}*}
{*{else}{$navSection}*}
{*{/if}*}
			{*</ul>*}
{*{/if}*}
{*{/foreach}*}
{assign var="mainNav" value=$page->getMainNav()}
{if !empty($mainNav)}
            <ul class="sideNav">
{foreach key=navItem item=navLink from=$mainNav}
				<li><a href="{$navLink}">{$navItem}</a></li>
{/foreach}
            </ul>
{/if}
{assign var="subNav" value=$page->getSubNav()}
{if !empty($subNav)}
            <ul class="sideNav">
                {include file='subNav.tpl' nav=$subNav sectionTitle='test'}
            </ul>
{/if}