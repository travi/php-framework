        <ul class="pagination{if !empty($class)} {$class}{/if}"{if !empty($paginatorId)} id="{$paginatorId}"{/if}>
            <li{if $offset lte 0} class="outOfRange"{/if}>
                <a href="{$path}/?offset={$prevOff|escape}{foreach from=$additionalParams key='param' item='paramValue'}&{$param}={$paramValue}{/foreach}" class="prev">
                    back
                </a>
            </li>
            {if $totalItems > $nextOff}
            <li class="{if $offset lte 0}outOfRange {/if}pipeDivider"> | </li>
            <li>
                <a href="{$path}/?offset={$nextOff|escape}{foreach from=$additionalParams key='param' item='paramValue'}&{$param}={$paramValue}{/foreach}" class="more">
                    more
                </a>
            </li>
            {/if}
        </ul>