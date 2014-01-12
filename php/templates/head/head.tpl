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
{if $showMetaViewport}
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
{/if}
{foreach item=link from=$page->getLinkTags()}
        <link   {if !empty($link['type'])}type="{$link['type']}" {/if}rel="{$link['rel']}"{if !empty($link['title'])} title="{$link['title']}"{/if} href="{$link['link']}" />
{/foreach}
{foreach item=style from=$dependencies['css']}
        <link   type="text/css" rel="stylesheet" media="screen" href="{$style}" />
{/foreach}
        <script type="text/javascript" src="/resources/shared/{if $page->isProduction()}min/{/if}thirdparty/modernizr/modernizr.min.js"> </script>
        <script type="text/javascript" src="/resources/thirdparty/travi-styles/icons/grunticon.loader.js"> </script>
        <script>
            grunticon([
                "/resources/thirdparty/travi-styles/icons/icons.data.svg.css",
                "/resources/thirdparty/travi-styles/icons/icons.data.png.css",
                "/resources/thirdparty/travi-styles/icons/icons.fallback.css"
            ]);
        </script>
        <noscript><link href="/resources/thirdparty/travi-styles/icons/icons.fallback.css" rel="stylesheet"></noscript>
    </head>
