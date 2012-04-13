<label for="{$field->getName()}">{$field->getLabel()}</label>
<select name="{$field->getName()}" id="{$field->getName()}" class="textInput">
{include file='components/form/options.tpl' options=$field->getOptions()}
</select>