<label for="{$field->getId()}">{$field->getLabel()}</label>
<select name="{$field->getName()}" id="{$field->getId()}" class="textInput"{if in_array('required', $field->getValidations())} required{/if}>
{include file='components/form/options.tpl' options=$field->getOptions()}
</select>
{assign var="error" value=$field->getValidationError()}
{if !empty($error)}<label for="{$field->getId()}" class="ui-state-error">{$error}</label>{/if}