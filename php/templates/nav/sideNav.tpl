{assign var="mainNav" value=$page->getMainNav()}
{if !empty($mainNav)}
            <nav class="sideNav" id="mainNav">
                <ul>
{foreach key=navItem item=navLink from=$mainNav}
    {if is_array($navLink)}
                    <li><a href="{$navLink['link']}">{$navItem}</a></li>
    {else}
                    <li><a href="{$navLink}">{$navItem}</a></li>
    {/if}
{/foreach}
                </ul>
            </nav>
{/if}
{assign var="adminNav" value=$page->getAdminNav()}
{if !empty($adminNav)}
            <nav class="sideNav" id="adminNav">
                <ul>
{foreach key=navItem item=navLink from=$adminNav}
                    <li><a href="{$navLink}">{$navItem}</a></li>
{/foreach}
                </ul>
            </nav>
{/if}
{assign var="subNav" value=$page->getSubNav()}
{if !empty($subNav)}
            <nav class="sideNav" id="subNav">
                <ul>
                    {include file='subNav.tpl' nav=$subNav sectionTitle=$page->getSiteSection()}
                </ul>
            </nav>
{/if}
