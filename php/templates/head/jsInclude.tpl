{foreach item=script from=$dependencies['js']}
        <script type="text/javascript" src="{$script}"> </script>
{/foreach}
{include file='head/jsInit.tpl'}