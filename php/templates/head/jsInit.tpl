{assign var='initArray' value=$page->getJsInits()}
{if !empty($initArray)}
        <script type="text/javascript" >
            $(document).ready(function(){literal}{{/literal}
{foreach item=init from=$page->getJsInits()}
                {$init}
{/foreach}
{include file="head/jsValidations.tpl" validations=$page->getValidations()}
            {literal}});{/literal}
        </script>
{/if}