{if isset($dependencies['jsInit'])}
    {assign var='initArray' value=$dependencies['jsInit']}
{/if}
{if isset($dependencies['clientTemplates'])}
    {assign var='externalTemplates' value=$dependencies['clientTemplates']}
{/if}
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
{foreach item=init from=$initArray}
                {$init}
{/foreach}
{/if}
{include file="head/jsValidations.tpl" validations=$dependencies['validations']}
        </script>
{/if}