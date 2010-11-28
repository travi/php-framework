{if is_a($section, 'NavSection')}
    {assign var=sectionContent value=$section->getContent()}
{else}
    {assign var=sectionContent value=$section}
{/if}
{if is_array($sectionContent)}{* && empty($section->getContent()[0])*}
							<ul class="bulletNav">
			{foreach key=key item=value from=$sectionContent}
				{if !is_array($value)}
								<li><a href="{$value}">{$key}</a></li>
				{elseif !empty($value['link'])}test
				{*{*}
					{*if($key != "Admin" && $key != "Admin Home")*}
					{*{*}
						{*$content .= '*}
								{*<li><a href="'.$value['link'].'">'.$key.'</a></li>';*}
					{*}*}
				{*}*}
				{else}
							    <li>{$key}
								    <ul>

					{foreach key=text item=link from=$value}
									    <li><a href="{$link}">{$text}</a></li>
					{/foreach}
                                    </ul>
                                </li>
				{/if}
			{/foreach}
							</ul>
{*}else if(is_array($this->sectionContent)){*}
			{*foreach($this->sectionContent as $contentPiece)*}
			{*{*}
				{*$content .= $contentPiece;*}
				{*if(is_object($contentPiece) && is_a($contentPiece,'ContentObject'))*}
				{*{*}
					{*$this->checkDependencies($contentPiece);*}
				{*}*}
			{*}*}
{*}else{*}
			{*$content .= $this->sectionContent;*}
			{*if(is_object($this->sectionContent) && is_a($this->sectionContent,'ContentObject'))*}
			{*{*}
				{*$this->checkDependencies($this->sectionContent);*}
			{*}*}
{/if}