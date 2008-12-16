{php}$this->assign('initArray',$this->_tpl_vars[page]->getJsInits());{/php}
{if !empty($initArray)}
		<script type="text/javascript" >
			$(document).ready(function(){literal}{{/literal}
{foreach item=init from=$page->getJsInits()}
				{$init}
{/foreach}
			{literal}});{/literal}
		</script>
{/if}