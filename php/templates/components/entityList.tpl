{assign var="list" value=$content['list']}
<a href="{$list->add->url}" class="item-action add-item dialog-target" title="Add">{$list->add->text}</a>

<ol class="entityList">
{foreach item=entity from=$list->getEntities()}
    {include file='components/entityBlock.tpl' entity=$entity}
{/foreach}
</ol>