<div class="entry">
    <div class="entry-message">
        <div class="{$content['status']}">{$content['message']}</div>
        <ul>
        {if !empty($content['location'])}
            <li><a href="{$content['location']}">Back to list</a></li>
        {/if}
        {if !empty($content['resource'])}
            <li><a href="{$content['resource']}">Modified Resource</a></li>
        {/if}
        </ul>
    </div>
</div>