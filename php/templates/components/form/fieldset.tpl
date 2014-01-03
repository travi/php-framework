<fieldset>
    <legend>{$fieldset->getLegend()}</legend>
    <ul class="fieldList">
        {foreach from=$fieldset->getFormElements() item=field}
        <li>
            {if is_a($field,'travi\framework\components\Forms\inputs\Input')}
                {include file=$field->getTemplate() field=$field}
            {elseif is_a($field,'travi\framework\components\Forms\choices\Choices')}
                {include file=$field->getTemplate() field=$field}
            {elseif is_a($field,'travi\framework\components\Forms\NoteArea')}
                {include file=$field->getTemplate() field=$field}
            {else}
                other
            {/if}
        </li>
        {/foreach}
    </ul>
</fieldset>