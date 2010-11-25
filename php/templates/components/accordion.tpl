		<dl class="accordion contentNav">
		{foreach key=title item=section from=$accordion->getSections()}
		    <dt>{$title}</dt>
				<dd>
				{assign var=sectionContent value=$section->getContent()}
                {if !empty($sectionContent)}
                    {include file=$section->getTemplate()}
                {else}&nbsp;
                {/if}
			    </dd>
		{/foreach}
		</dl>