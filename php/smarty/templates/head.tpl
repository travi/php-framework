<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>{$page->title}</title>
{section name=tags loop=$page->metatags}
		{$page->metatags[tags]}
{/section}
{section name=feeds loop=$page->feeds}
		{$page->feeds[feeds]}
{/section}
{foreach item=styles from=$page->stylesheets}
		<link href="{$styles}" rel="stylesheet" type="text/css" media="screen" />
{/foreach}
{section name=alt loop=$page->altStyles}
		<link href="{$page->altStyles[alt]}" rel="alternate stylesheet" type="text/css" media="screen" />
{/section}
{section name=scripts loop=$page->scripts}
		<script src="{$page->scripts[scripts]}" type="text/javascript" > </script>
{/section}
{if !empty($page->jsInits)}
{include file='/home/.tookie/travi/include/php/smarty/templates/jsInit.tpl'}

{/if}
	</head>
