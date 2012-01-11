<ol class="entityList">
{assign var=primaryActions value=$section->getActions()}
{foreach item=entity from=$section->getEntities()}
    {include file='components/entityBlock.tpl' entity=$entity primaryActions=$primaryActions}
{/foreach}
</ol>