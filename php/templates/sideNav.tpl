{assign var="mainNav" value=$page->getMainNav()}
{if !empty($mainNav)}
            <ul class="sideNav" id="mainNav">
{foreach key=navItem item=navLink from=$mainNav}
				<li><a href="{$navLink}">{$navItem}</a></li>
{/foreach}
            </ul>
{/if}
{assign var="adminNav" value=$page->getAdminNav()}
{if !empty($adminNav)}
            <ul class="sideNav" id="adminNav">
{foreach key=navItem item=navLink from=$adminNav}
				<li><a href="{$navLink}">{$navItem}</a></li>
{/foreach}
            </ul>
{/if}
{assign var="subNav" value=$page->getSubNav()}
{if !empty($subNav)}
            <ul class="sideNav" id="subNav">
                {include file='subNav.tpl' nav=$subNav sectionTitle='test'}
            </ul>
{/if}