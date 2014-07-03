<fieldset>
    <legend>{$field->getLabel()}</legend>
    <ol class="choices">
    {foreach from=$field->getOptions() item=option}
        <li>
            <label>
                <input type="{$field->getType()}" name="{$field->getName()}"
                       value="{if !empty($option['value'])}{$option['value']}{else}{$option['option']}{/if}"
                       class="{$field->getClass()}"{if $option['disabled']} disabled{/if}{if $option['selected']||!empty($this->value) && (($option['option'] eq $this->value)||($option['value'] eq $this->value))} checked{/if}/>
                {$option['option']}
            </label>
        </li>
    {/foreach}
    </ol>
    {assign var="error" value=$field->getValidationError()}
    {if !empty($error)}<label class="ui-state-error">{$error}</label>{/if}
</fieldset>