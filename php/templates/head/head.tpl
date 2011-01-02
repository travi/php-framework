<html>
    <head>
        <title>{$page->getTitle()}</title>
{foreach item=tag from=$page->getMetatags()}
        {$tag}
{/foreach}
{foreach item=link from=$page->getLinkTags()}
        <link   {if !empty($link['type'])}type="{$link['type']}" {/if}rel="{$link['rel']}"{if !empty($link['title'])} title="{$link['title']}"{/if} href="{$link['link']}" />
{/foreach}
{foreach item=alt from=$page->getAltStyles()}
        <link   type="text/css" rel="alternate stylesheet" media="screen" href="{$page->getProperFile($alt)}?{$page->getUrlFingerprint()}" />
{/foreach}
{foreach item=style from=$page->getStylesheets()}
        <link   type="text/css" rel="stylesheet" media="screen" href="{$page->getProperFile($style)}?{$page->getUrlFingerprint()}" />
{/foreach}
{foreach item=script from=$page->getScripts()}
        <script type="text/javascript" src="{$page->getProperFile($script)}?{$page->getUrlFingerprint()}" > </script>
{/foreach}
{include file='head/jsInit.tpl'}
{include file='head/clientTemplates.tpl'}
{$page->goog_analytics()}
    </head>
