{assign var="list" value=$content['list']}
{assign var="emptyStateMessage" value="There are currently no {$list->pluralType|lower} in the system."}
<a href="{$list->add->url}" class="item-action add-item dialog-target" title="Add">{$list->add->text}</a>

<h4 class="entity-list-header">{$list->pluralType|ucfirst}</h4>
{if empty($list->getEntities())}<p class="empty-list-message">{$emptyStateMessage}</p>{/if}

<ol class="entityList" travi-empty-state-message="{$emptyStateMessage}">
{foreach item=entity from=$list->getEntities()}
    {include file='components/entityBlock.tpl' entity=$entity}
{/foreach}
</ol>