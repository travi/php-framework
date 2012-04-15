        <dl class="accordion contentNav">
        {foreach key=title item=section from=$accordion->getSections()}
            <dt>{$title}</dt>
                <dd>
                {if !empty($section)}
                    {assign var="sectionTemplate" value=$accordion->getSectionTemplate()}
                    {if !empty($sectionTemplate)}
                        {include file=$sectionTemplate section=$section}
                    {else}
                        {include file="nav/navSection.tpl"}
                    {/if}
                {else}&nbsp;
                {/if}
                </dd>
        {/foreach}
        </dl>