		<dl class="accordion contentNav">
		{foreach key=title item=section from=$accordion->getSections()}
		    <dt>{$title}</dt>
				<dd>
                {if !empty($section)}
                    {include file="nav/navSection.tpl"}
                {else}&nbsp;
                {/if}
			    </dd>
		{/foreach}
		</dl>