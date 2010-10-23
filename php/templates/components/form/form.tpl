<form name="{$form->getName()}" method="{$form->getMethod()}" action="{$form->getAction()}">
		{*if($this->contains("FileInput"))*}
		{*{*}
			{*$this->encType = "multipart/form-data";*}
			{*$form .= ' enctype="'.$this->encType.'"';*}
		{*}*}
{foreach from=$form->getFieldsets() item=fieldset}
    {include file="components/form/fieldset.tpl" fieldset=$fieldset}
{/foreach}
</form>