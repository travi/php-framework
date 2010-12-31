{foreach key=name item=template from=$page->getClientTemplates()}
                <script type="text/x-jQuery-tmpl" id="{$name}">
                    {$template}
                </script>
{/foreach}