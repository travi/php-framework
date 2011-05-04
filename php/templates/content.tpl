{assign var='pageTemplate' value=$page->getPageTemplate()}
{assign var='content' value=$page->getContent()}
{if !empty($pageTemplate)}
    {include file="pages/$pageTemplate" content=$page->getContent()}
{else}
    {if is_array($content)}
        {foreach item=contentObject from=$content}
    {include file=$contentObject->getTemplate() object=$contentObject}
        {/foreach}
    {else}{$content}
    {/if}

{/if}

