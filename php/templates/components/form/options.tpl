{foreach from=$options item=option}
    <option{if !empty($option['value']) || $option['option'] eq 'Select One'} value="{$option['value']}"{/if}{if $option['disabled']} disabled{/if}{if $option['selected']||(!empty($this->value)&&(($option['option'] eq $this->value)||($option['value'] eq $this->value)))} selected{/if}>
        {$option['option']}
    </option>
{/foreach}