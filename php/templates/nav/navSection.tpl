{if is_a($section, 'NavSection')}
    {assign var=sectionContent value=$section->getContent()}
{else}
    {assign var=sectionContent value=$section}
{/if}
{if is_array($sectionContent)}
                            <ul class="bulletNav">
            {foreach key=key item=value from=$sectionContent}
                {if !is_array($value)}{*TODO: Find a way to normalize the format of link arrays without requiring all to be persisted consistently*}
                    {if $key ne "Admin" && $key ne "Admin Home"}{*TODO: This filter should probably be moved from teh view to the controller *}
                                <li><a href="{$value}">{$key}</a></li>
                    {/if}
                {elseif !empty($value['link'])  && !empty($value['text'])}
                                <li><a href="{$value['link']}">{$value['text']}</a></li>
                {elseif !empty($value['link'])}
                                <li><a href="{$value['link']}">{$key}</a></li>
                {else}
                                <li>{$key}
                                    <ul>

                    {foreach key=text item=link from=$value}
                                        <li><a href="{$link}">{$text}</a></li>
                    {/foreach}
                                    </ul>
                                </li>
                {/if}
            {/foreach}
                            </ul>
{/if}