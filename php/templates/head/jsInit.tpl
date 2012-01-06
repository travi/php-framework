{assign var='initArray' value=$dependencies['jsInit']}
{assign var='externalTemplates' value=$dependencies['clientTemplates']}
{if !empty($initArray) || !empty($externalTemplates)}
    <script type="text/javascript" >
{foreach key=name item=path from=$externalTemplates}
        travi.templates.preLoadTemplate('{$name}', '{$path}');
{/foreach}
            $(document).ready(function () {literal}{{/literal}
{foreach item=init from=$initArray}
                {$init}
{/foreach}
{include file="head/jsValidations.tpl" validations=$dependencies['validations']}
            {literal}});{/literal}
        </script>
{/if}