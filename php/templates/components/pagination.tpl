        <ul class="pagination">
            <li{if $offset lte 0} class="outOfRange"{/if}><a href="{$path}/?offset={$prevOff|escape}" id="{$prevId}">back</a></li>
            {if $totalItems > $nextOff}
            <li class="{if $offset lte 0}outOfRange {/if}pipeDivider"> | </li>
            <li><a href="{$path}/?offset={$nextOff|escape}" id="{$moreId}">more</a></li>
            {/if}
        </ul>