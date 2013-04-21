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
                         <li><a href="{$navLink['link']}{$subLink}">{$subItem}</a></li>
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
