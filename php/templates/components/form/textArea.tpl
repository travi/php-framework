<label for="{$field->getId()}">{$field->getLabel()}</label>
{assign var="rowCount" value=$field->getRows()}
<textarea name="{$field->getName()}" id="{$field->getId()}"{if !empty($rowCount)} rows="{$rowCount}"{/if} class="{$field->getClass()}"{if in_array('required', $field->getValidations())} required{/if}>
{$field->getValue()}
</textarea>
{if !empty($error)}<label for="{$field->getId()}" class="ui-state-error">{$error}</label>{/if}