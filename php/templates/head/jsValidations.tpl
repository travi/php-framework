{foreach from=$validations key=form item=list}
                $('form{if !empty($form)}[name="{$form}"]{/if}').validate({literal}{{/literal}
{if $form->debug}
                    debug: true,
{/if}
                    errorClass: 'ui-state-error'
                {literal}});{/literal}
{/foreach}