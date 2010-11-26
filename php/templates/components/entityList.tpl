<ol class="entityList">
{assign var=primaryActions value=$entityList->getActions()}
{foreach item=entity from=$entityList->getEntities()}
    <li>{include file='components/entityBlock.tpl' entity=$entity primaryActions=$primaryActions}</li>
{/foreach}
</ol>