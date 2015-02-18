<label for="{$field->getId()}">{$field->getLabel()}</label>
<div class="formBlock">
    <textarea name="{$field->getName()}" id="{$field->getId()}" rows="{$field->getRows()}" class="{$field->getClass()}"{if in_array('required', $field->getValidations())} required{/if}>
        {*'.htmlentities($this->value).'*}
        {$field->getValue()|escape:'htmlall'}
    </textarea>
</div>
{assign var="error" value=$field->getValidationError()}
{if !empty($error)}<label for="{$field->getId()}" class="ui-state-error">{$error}</label>{/if}