{assign var='initArray' value=$dependencies['jsInit']}
{assign var='externalTemplates' value=$dependencies['clientTemplates']}
{if !empty($initArray) || !empty($externalTemplates)}
        <script type="text/javascript" >
{if !empty($externalTemplates)}
            travi.templates.preLoad({
{foreach key=name item=path from=$externalTemplates}
                '{$name}': '{$path}'{if !$smarty.foreach.val.last},{/if}

{/foreach}
            });
{/if}
{if !empty($initArray)}
            $(function () {literal}{{/literal}
{foreach item=init from=$initArray}
                {$init}
{/foreach}
{include file="head/jsValidations.tpl" validations=$dependencies['validations']}
            {literal}});{/literal}
{/if}
        </script>
{/if}