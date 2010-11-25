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