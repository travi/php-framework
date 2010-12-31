<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
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
{include file='jsInit.tpl'}
{include file='clientTemplates.tpl'}
{$page->goog_analytics()}
	</head>