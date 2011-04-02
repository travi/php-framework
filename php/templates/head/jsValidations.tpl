{foreach from=$validations key=form item=list}
                $('form[name="{$form}"]').validate({literal}{{/literal}
{if $form->debug}
                    debug: true,
{/if}
                    errorClass: 'ui-state-error',
                    rules: {literal}{{/literal}
{foreach from=$list key=field item=vals name=val}
                        {$field}: {if sizeof($vals) eq 1 && $vals[0] == 'required'}"required"{if !$smarty.foreach.val.last},{/if}
                        
{elseif sizeof($vals) eq 1}"required"{if !$smarty.foreach.val.last},{/if} {*should this ever happen?*}
{else}{literal}{{/literal}
                            required: true,
{if in_array('email', $vals)}
                            email: true
{/if}
                        {literal}}{/literal}{if !$smarty.foreach.val.last},{/if}
                        
{/if}
{/foreach}
                    }
                {literal}});{/literal}
{/foreach}