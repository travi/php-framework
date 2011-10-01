<!DOCTYPE html public "✰">
{*paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/*}
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
    <head>
        <title>{$page->getDecoratedTitle()}</title>
{foreach item=tag from=$page->getMetatags()}
        {$tag}
{/foreach}
{if $isMobile}
    {*May need to make this configurable per site*}
        <meta name="viewport" content="width=device-width; initial-scale=1.0;">
{/if}
{foreach item=link from=$page->getLinkTags()}
        <link   {if !empty($link['type'])}type="{$link['type']}" {/if}rel="{$link['rel']}"{if !empty($link['title'])} title="{$link['title']}"{/if} href="{$link['link']}" />
{/foreach}
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
{foreach item=style from=$dependencies['css']}
        <link   type="text/css" rel="stylesheet" media="screen" href="{$style}" />
{/foreach}
        <script type="text/javascript" src="/resources/shared/{if $page->isProduction()}min/{/if}js/modernizr/modernizr.min.js"> </script>
{foreach item=script from=$dependencies['js']}
        <script type="text/javascript" src="{$script}"> </script>
{/foreach}
{include file='head/jsInit.tpl'}
{include file='head/clientTemplates.tpl'}
{$page->goog_analytics()}
    </head>
