		<script type="text/javascript" >
			$(document).ready(function(){literal}{{/literal}
{foreach item=init from=$page->getJsInits()}
				{$init}
{/foreach}			{literal}});{/literal}
		</script>