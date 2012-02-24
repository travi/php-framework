<label for="{$field->getName()}">{$field->getLabel()}</label>
{assign var="rowCount" value=$field->getRows()}
<textarea name="{$field->getName()}" id="{$field->getName()}"{if !empty($rowCount)} rows="{$rowCount}"{/if} class="{$field->getClass()}">
{$field->getValue()}
</textarea>