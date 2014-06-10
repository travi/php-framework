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
                {assign var=primaryActions value=$entity->getPrimaryActions()}
                {if !empty($primaryActions['remove'])}
                <li class="{$primaryActions['remove']->text|lower}-item">
                    <form action="{$primaryActions['remove']->url}" class="item-action" method="post">
                        <input type="hidden" name="id" value="{$entity->id}"/>
                        <input type="hidden" name="_method" value="delete"/>
                        <input type="submit" value="{$primaryActions['remove']->text}"/>
                    </form>
                </li>
                {/if}
                <li class="{$primaryActions['edit']->text|lower}-item">
                    <a class="item-action icon-{$primaryActions['edit']->text|lower} dialog-target" href="{$primaryActions['edit']->url}">
                        {$primaryActions['edit']->text}
                    </a>
                </li>
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