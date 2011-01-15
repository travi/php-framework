			<div class="ui-tabs">
				<ul>
{foreach key="title" item="section" from=$lists->getSections()}
					<li><a href="#{$title|replace:' ':'_'|lower}"><span>{$title}</span></a></li>
{/foreach}
				</ul>
{foreach key="title" item="section" from=$lists->getSections()}
				<div id="{$title|replace:' ':'_'|lower}">
{if !empty($section)}
				    {$section}
{else}
				    &nbsp;
{/if}
				</div>
{/foreach}
            </div>