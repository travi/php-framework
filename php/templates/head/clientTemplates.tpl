{foreach key=name item=template from=$page->getClientTemplates()}
                <script type="text/x-jquery-tmpl" id="{$name}">
                    {$template}
                </script>
{/foreach}