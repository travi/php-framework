{foreach item=style from=$dependencies['css']}
    <link   type="text/css" rel="stylesheet" media="screen" href="{$style}" />
{/foreach}