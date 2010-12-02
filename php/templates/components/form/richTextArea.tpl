<label for="{$field->getName()}">{$field->getLabel()}</label>
<div class="formBlock">
    <textarea name="{$field->getName()}" id="{$field->getName()}" rows="{$field->getRows()}" class="{$field->getClass()}">
        {*'.htmlentities($this->value).'*}
        {$field->getValue()|escape:'htmlall'}
    </textarea>
</div>