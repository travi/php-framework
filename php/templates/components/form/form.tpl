{assign var="encType" value=$form->getEncType()}
{assign var="formName" value=$form->getName()}
<form{if !empty($formName)}
 name="{$form->getName()}"
{/if} method="{$form->getMethod()}" action="{$form->getAction()}"{if !empty($encType)}
 enctype="{$encType}"{/if}{if !empty($form->key)}
 travi-form-key="{$form->key}"{/if}>
{foreach from=$form->getFormElements() item=formElement}
    {if is_a($formElement, 'travi\framework\components\Forms\Fieldset')}
        {include file="components/form/fieldset.tpl" fieldset=$formElement}
    {elseif is_a($formElement,'travi\framework\components\Forms\inputs\Input')}
        {include file=$formElement->getTemplate() field=$formElement}
    {elseif is_a($formElement,'travi\framework\components\Forms\choices\Choices')}
        {include file=$formElement->getTemplate() field=$formElement}
    {/if}
{/foreach}
</form>