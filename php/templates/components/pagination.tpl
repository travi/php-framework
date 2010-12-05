        <ul class="pagination">
            <li{if $offset lte 0} class="outOfRange"{/if}><a href="{$path}/?offset={$prevOff|escape}" id="{$prevId}">back</a></li>
            {if $totalItems > $nextOff}
            <li{if $offset lte 0} class="outOfRange pipeDivider"{/if}> | </li>
            <li><a href="{$path}/?offset={$nextOff|escape}" id="{$moreId}">more</a></li>
            {/if}
        </ul>