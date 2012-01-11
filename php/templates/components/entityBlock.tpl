            <li class="entityBlock {$entity->getType()}">
                <dl>
                    <dt>{$entity->getTitle()}</dt>
        {foreach item=detail from=$entity->getDetails()}
                        <dd>{$detail}</dd>
        {/foreach}
                        <dd>
                            <ul class="actions">

        {foreach key=text item=details from=$primaryActions}
            {if empty($item->activeActions[$text])}
                                <li class="{$text|lower}-item">
                                {if $text eq 'Remove'}
                                    <form action="{$details['link']}{$entity->getId()}" class="item-action" method="post">
                                        <input type="hidden" name="id" value="{$entity->getId()}" />
                                        <input type="hidden" name="_method" value="delete">
                                    </form>
                                {else}
                                    <a class="item-action" href="{$details['link']}{$entity->getId()}">
                                        {$text}
                                    </a>
                                {/if}
                                </li>
            {/if}
        {/foreach}

                            </ul>
        {assign var="extraRows" value=$entity->getExtraActionRows()}
        {if !empty($extraRows)}
            {foreach from=$extraRows item=row}
                            <ul class="actions">
                {foreach from=$row item=actions}
                    {if !isset($actions['active']) || $actions['active'] == TRUE}
                                    <li class="item-action {$actions['class']}"><a href="{$actions['link']}{$entity->getId()}"
                        {*if(!empty($actions["$action_text"]['confirmation']))*}
                        {*{*}
                            {*$entity .= ' onclick="if (confirm('."'".$this->preConf.$actions["$action_text"]['confirmation']."'".')) return true; else return false;"';*}
                        {*}*}>{$actions['text']}</a></li>
                    {/if}
                {/foreach}
                        </ul>
            {/foreach}
        {/if}
                    </dd>
                </dl>
            </li>