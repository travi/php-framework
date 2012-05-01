<ul class="menuBar">
    {foreach key=title item=section from=$lists->getSections()}
        <li>
            {$title}
            {if !empty($section)}
                {assign var="sectionTemplate" value=$lists->getSectionTemplate()}
                {if !empty($sectionTemplate)}
                    {include file=$sectionTemplate section=$section}
                {else}
                    {include file="nav/navSection.tpl"}
                {/if}
            {/if}
        </li>
    {/foreach}
</ul>