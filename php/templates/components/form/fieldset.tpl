<fieldset>
    <legend>{$fieldset->getLegend()}</legend>
    <ul class="fieldList">
		{foreach from=$fieldset->getFields() item=field}
		<li>
		    {*Need to handle hidden, button, and options*}
            {if is_a($field,'TextArea')}
                {include file="components/form/textArea.tpl" field=$field}
            {else} {if is_a($field,'Input')}
                {include file="components/form/input.tpl" field=$field}
                {else}
                other
                {/if}
            {/if}
        </li>
		{/foreach}
    </ul>
</fieldset>