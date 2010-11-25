<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>{$page->getTitle()}</title>
{foreach item=tag from=$page->getMetatags()}
		{$tag}
{/foreach}
{foreach item=feed from=$page->getFeeds()}
		<link rel="alternate" type="application/rss+xml" title="RSS" href="{$feed}" />
{/foreach}
{foreach item=alt from=$page->getAltStyles()}
		<link  href="{$page->getProperFile($alt)}?{$page->getUrlFingerprint()}" rel="alternate stylesheet" type="text/css" media="screen" />
{/foreach}
{$page->getWpHead()}
{foreach item=style from=$page->getStylesheets()}
		<link  href="{$page->getProperFile($style)}?{$page->getUrlFingerprint()}" rel="stylesheet" type="text/css" media="screen" />
{/foreach}
{foreach item=script from=$page->getScripts()}
		<script src="{$page->getProperFile($script)}?{$page->getUrlFingerprint()}" type="text/javascript" > </script>
{/foreach}
{include file='jsInit.tpl'}
{$page->goog_analytics()}
	</head>