<ol class="entityList">
{foreach item=entity from=$section->getEntities()}
    {include file='components/entityBlock.tpl' entity=$entity}
{/foreach}
</ol>