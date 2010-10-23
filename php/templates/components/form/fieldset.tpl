<fieldset>
    <legend>{$fieldset->getLegend()}</legend>
    <ul class="fieldList">
		{foreach from=$fieldset->getFields() item=field}
		<li>
		    {*Need to handle hidden, button*}
		    {if is_a($field,'Input')}
                {if is_a($field,'TextArea')}
                    {include file="components/form/textArea.tpl" field=$field}
                {else}
                    {include file="components/form/input.tpl" field=$field}
                {/if}
            {elseif is_a($field,'Choices')}
                {if is_a($field,'SelectionBox')}
                    {include file="components/form/selectionBox.tpl" field=$field}
                {else}
                    {include file="components/form/choices.tpl" field=$field}
                {/if}
            {else}
                other
            {/if}
        </li>
		{/foreach}
    </ul>
</fieldset>