<form name="{$form->getName()}" method="{$form->getMethod()}" action="{$form->getAction()}">
		{*if($this->contains("FileInput"))*}
		{*{*}
			{*$this->encType = "multipart/form-data";*}
			{*$form .= ' enctype="'.$this->encType.'"';*}
		{*}*}
{foreach from=$form->getFieldsets() item=fieldset}
    {if is_a($fieldset, 'Fieldset')}
        {include file="components/form/fieldset.tpl" fieldset=$fieldset}
    {elseif is_a($fieldset,'Input')}
        {include file=$fieldset->getTemplate() field=$fieldset}
    {/if}
{/foreach}
</form>