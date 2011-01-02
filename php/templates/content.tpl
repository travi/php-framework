			    {assign var='pageTemplate' value=$page->getPageTemplate()}
			    {if !empty($pageTemplate)}
			        {include file="pages/$pageTemplate" content=$page->getContent()}
				{else}
				    {$page->getContent()}
			    {/if}