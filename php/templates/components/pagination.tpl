        <ul class="pagination">
            <li{if $offset lte 0} class="outOfRange"{/if}><a href="{$path}/?offset={$prevOff|escape}" id="{$prevId}" class="prev">back</a></li>
            {if $totalItems > $nextOff}
            <li class="{if $offset lte 0}outOfRange {/if}pipeDivider"> | </li>
            <li><a href="{$path}/?offset={$nextOff|escape}" id="{$moreId}" class="more">more</a></li>
            {/if}
        </ul>