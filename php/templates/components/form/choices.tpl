<fieldset>
    <legend>{$field->getLabel()}</legend>
    <ol class="choices">
    {foreach from=$field->getOptions() item=option}
        <li>
            <label>
                <input type="{$field->getType()}" name="{$field->getName()}" value="{$option->value}" class="{$field->getClass()}"{if $option->selected} checked{/if}/>
                {$option->text}
            </label>
        </li>
    {/foreach}
    </ol>
    {assign var="error" value=$field->getValidationError()}
    {if !empty($error)}<label class="ui-state-error">{$error}</label>{/if}
</fieldset>