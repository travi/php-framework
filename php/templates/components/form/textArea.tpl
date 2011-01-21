<label for="{$field->getName()}">{$field->getLabel()}</label>
<textarea name="{$field->getName()}" id="{$field->getName()}" rows="{$field->getRows()}" class="{$field->getClass()}">
{$field->getValue()}
</textarea>