{assign var='initArray' value=$page->getJsInits()}
{assign var='externalTemplates' value=$page->getExternalClientTemplates()}
{if !empty($initArray) || !empty($externalTemplates)}
        <script type="text/javascript" >
            $(document).ready(function () {literal}{{/literal}
{foreach key=name item=path from=$externalTemplates}
                travi.loadTemplate('{$path}', '{$name}');
{/foreach}
{foreach item=init from=$page->getJsInits()}
                {$init}
{/foreach}
{include file="head/jsValidations.tpl" validations=$page->getValidations()}
            {literal}});{/literal}
        </script>
{/if}