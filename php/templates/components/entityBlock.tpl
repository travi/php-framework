			<div class="entityBlock {$entity->getType()}">
	      		<dl>
	        		<dt>{$entity->getTitle()}</dt>
		{foreach item=detail from=$entity->getDetails()}
						<dd>{$detail}</dd>
        {/foreach}
						<dd>
							<ul class="actions">

		{foreach key=text item=details from=$primaryActions}
			{if empty($item->activeActions[$text])}
								<li class="{$text|lower}-item">
                                {if $text eq 'Remove'}
                                    <form action="index.php" class="item-action">
                                        <input type="submit" value="Remove" />
								    </form>
                                {else}
								    <a class="item-action" href="{$details['link']}{$entity->getId()}">
                                        {$text}
                                    </a>
                                {/if}
                                </li>
			{/if}
		{/foreach}

							</ul>

		{if !empty($this->extraActionRows)}
		    extra row
		{*{*}
			{*foreach($this->extraActionRows as $row)*}
			{*{*}
				{*$entity .= '*}
							{*<ul class="actions">';*}
				{*foreach($row as $actions)*}
				{*{*}
					{*if(!isset($actions['active']) || $actions['active'] == TRUE)*}
					{*{*}
						{*$entity .= '*}
									{*<li class="item-action '.$actions['class'].'"><a href="'.$actions['link'].$this->id.'"';*}
						{*if(!empty($actions["$action_text"]['confirmation']))*}
						{*{*}
							{*$entity .= ' onclick="if (confirm('."'".$this->preConf.$actions["$action_text"]['confirmation']."'".')) return true; else return false;"';*}
						{*}*}

						{*$entity .= '>'.$actions['text'].'</a></li>';*}
					{*}*}
				{*}*}
				{*$entity .= '*}
						{*</ul>';*}
			{*}*}
		{/if}
					</dd>
  				</dl>
  			</div>