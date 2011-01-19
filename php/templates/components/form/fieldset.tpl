<fieldset>
    <legend>{$fieldset->getLegend()}</legend>
    <ul class="fieldList">
		{foreach from=$fieldset->getFields() item=field}
		<li>
		    {if is_a($field,'Input')}
                {include file=$field->getTemplate() field=$field}
            {elseif is_a($field,'Choices')}
                {include file=$field->getTemplate() field=$field}
            {elseif is_a($field,'NoteArea')}
                {include file=$field->getTemplate() field=$field}
            {else}
                other
            {/if}
        </li>
		{/foreach}
    </ul>
</fieldset>