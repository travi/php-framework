<form name="{$form->getName()}" method="{$form->getMethod()}" action="{$form->getAction()}">
		{*if($this->contains("FileInput"))*}
		{*{*}
			{*$this->encType = "multipart/form-data";*}
			{*$form .= ' enctype="'.$this->encType.'"';*}
		{*}*}
{foreach from=$form->getFormElements() item=formElement}
    {if is_a($formElement, 'Fieldset')}
        {include file="components/form/fieldset.tpl" fieldset=$formElement}
    {elseif is_a($formElement,'Input')}
        {include file=$formElement->getTemplate() field=$formElement}
    {/if}
{/foreach}
</form>