			<div class="ui-tabs">
				<ul>
{foreach key="title" item="section" from=$lists->getSections()}
					<li><a href="#{$title|replace:' ':'_'|lower}"><span>{$title}</span></a></li>
{/foreach}
				</ul>
{foreach key="title" item="section" from=$lists->getSections()}
				<div id="{$title|replace:' ':'_'|lower}">
    {if !empty($section)}
        {assign var="sectionTemplate" value=$lists->getSectionTemplate()}
        {if !empty($sectionTemplate)}
            {include file=$sectionTemplate section=$section}
        {else}
				    {$section}
        {/if}
    {else}
				    &nbsp;
    {/if}
				</div>
{/foreach}
            </div>