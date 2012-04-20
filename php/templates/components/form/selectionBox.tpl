<label for="{$field->getName()}">{$field->getLabel()}</label>
<select name="{$field->getName()}" id="{$field->getName()}" class="textInput">
{include file='components/form/options.tpl' options=$field->getOptions()}
</select>
{assign var="error" value=$field->getValidationError()}
{if !empty($error)}<label class="ui-state-error">{$error}</label>{/if}