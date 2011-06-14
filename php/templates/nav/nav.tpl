                <ul>
    {foreach from=$nav item=item}
                    <li><a href="{$item['link']}">{$item['text']}</a>{if !empty($item['subLinks'])}
                        <ul>
        {foreach from=$item['subLinks'] item=subItem}
                            <li><a href="{$subItem['link']}">{$subItem['text']}</a></li>
        {/foreach}
                        </ul>
                    {/if}</li>
    {/foreach}
                </ul>