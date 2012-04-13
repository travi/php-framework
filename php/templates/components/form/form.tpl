{assign var="encType" value=$form->getEncType()}
{assign var="formName" value=$form->getName()}
<form{if !empty($formName)}
 name="{$form->getName()}"
{/if} method="{$form->getMethod()}" action="{$form->getAction()}"{if !empty($encType)}
 enctype="{$encType}"{/if}>
{foreach from=$form->getFormElements() item=formElement}
    {if is_a($formElement, 'Fieldset')}
        {include file="components/form/fieldset.tpl" fieldset=$formElement}
    {elseif is_a($formElement,'Input')}
        {include file=$formElement->getTemplate() field=$formElement}
    {elseif is_a($formElement,'Choices')}
        {include file=$formElement->getTemplate() field=$formElement}
    {/if}
{/foreach}
</form>