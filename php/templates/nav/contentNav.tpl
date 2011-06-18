{assign var="nav" value=$page->getMainNav()}
{if !empty($nav)}
            <ul id="{$id}">
{foreach key=navItem item=navLink from=$nav}
    {if is_array($navLink)}
                <li>
                    <a href="{$navLink['link']}">{$navItem}</a>
        {if (!empty($navLink['subLinks']))}
                    <ul>
            {foreach key=subItem item=subLink from=$navLink['subLinks']}
                         <li><a href="{$subLink['link']}">{$subItem}</a></li>
            {/foreach}
                    </ul>
        {/if}
                </li>
    {else}
                <li><a href="{$navLink}">{$navItem}</a></li>
    {/if}
{/foreach}
            </ul>
{/if}
{*{assign var="adminNav" value=$page->getAdminNav()}*}
{*{if !empty($adminNav)}*}
{*            <ul class="sideNav" id="adminNav">*}
{*{foreach key=navItem item=navLink from=$adminNav}*}
{*                <li><a href="{$navLink}">{$navItem}</a></li>*}
{*{/foreach}*}
{*            </ul>*}
{*{/if}*}
{*{assign var="subNav" value=$page->getSubNav()}*}
{*{if !empty($subNav)}*}
{*            <ul class="sideNav" id="subNav">*}
{*                {include file='subNav.tpl' nav=$subNav sectionTitle=$page->getSiteSection()}*}
{*            </ul>*}
{*{/if}*}
