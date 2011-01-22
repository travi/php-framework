<ol class="entityList">
{assign var=primaryActions value=$section->getActions()}
{foreach item=entity from=$section->getEntities()}
    <li>{include file='components/entityBlock.tpl' entity=$entity primaryActions=$primaryActions}</li>
{/foreach}
</ol>