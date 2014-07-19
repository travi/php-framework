{foreach from=$options item=option}
    <option{if !empty($option->value) || $option->text eq 'Select One'} value="{$option->value}"{/if}{if $option->selected} selected{/if}>
        {$option->text}
    </option>
{/foreach}