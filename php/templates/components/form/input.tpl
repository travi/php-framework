{assign var='class' value=$field->getClass()}
{assign var='value' value=$field->getValue()}
{assign var="error" value=$field->getValidationError()}
<input type="{$field->getType()}" name="{$field->getName()}" id="{$field->getId()}"{if in_array('required', $field->getValidations())} required{/if}{if !empty($value)} value="{$field->getValue()}"{/if}{if !empty($class)} class="{$class}"{/if}/>
{if !empty($error)}<label for="{$field->getId()}" class="ui-state-error">{$error}</label>{/if}