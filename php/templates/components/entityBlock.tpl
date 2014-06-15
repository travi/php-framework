<li class="entityBlock {$entity->getType()}" travi-self="{$entity->selfLink}">
    <dl>
        <dt>{$entity->getTitle()}</dt>
    {if $entity->getSummary()}
        <dd>{$entity->getSummary()}</dd>
    {/if}
    {foreach item=detail from=$entity->getDetails()}
        <dd>{$detail}</dd>
    {/foreach}
        <dd>
            <ul class="actions">
                {foreach item=action from=$entity->getPrimaryActions()}
                <li class="{$action->text|lower}-item">
                    <a class="item-action icon-{$action->text|lower} dialog-target" href="{$action->url}">
                        {$action->text}
                    </a>
                </li>
                {/foreach}
            </ul>
        {assign var="extraRows" value=$entity->getExtraActionRows()}
        {if !empty($extraRows)}
            {foreach from=$extraRows item=row}
                <ul class="actions">
                    {foreach from=$row item=actions}
                        {if !isset($actions['active']) || $actions['active'] === TRUE}
                            <li class="{$actions['class']}">
                                <a class="item-action" href="{$actions['link']}{$entity->getId()}">
                                    {$actions['text']}
                                </a>
                            </li>
                        {/if}
                    {/foreach}
                </ul>
            {/foreach}
        {/if}
        </dd>
    </dl>
</li>