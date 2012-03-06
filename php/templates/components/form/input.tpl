{assign var='class' value=$field->getClass()}
{assign var='value' value=$field->getValue()}
<input type="{$field->getType()}" name="{$field->getName()}" id="{$field->getName()}"{if !empty($value)} value="{$field->getValue()}"{/if}{if !empty($class)} class="{$class}"{/if}/>