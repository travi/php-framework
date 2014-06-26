{assign var="list" value=$content['list']}
<a href="{$list->add->url}" class="item-action add-item dialog-target" title="Add">{$list->add->text}</a>

<h4>{$list->pluralType|ucfirst}</h4>
{if empty($list)}<p class="empty-list-message">There are currently no {$list->pluralType} in the system.</p>{/if}

<ol class="entityList">
{foreach item=entity from=$list->getEntities()}
    {include file='components/entityBlock.tpl' entity=$entity}
{/foreach}
</ol>