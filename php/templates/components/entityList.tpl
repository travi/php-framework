{assign var="entities" value=$section->getEntities()}
<a href="{$entities->add->url}" class="item-action add-item dialog-target" title="Add">{$entities->add->text}</a>

<ol class="entityList">
{foreach item=entity from=$entities}
    {include file='components/entityBlock.tpl' entity=$entity}
{/foreach}
</ol>