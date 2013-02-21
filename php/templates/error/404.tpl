<h2>Page Not Found</h2>
<p>The page you were looking for could not be found</p>
{if !$page->isProduction()}
    {if !empty($content['errorMessage'])}
<p>{$content['errorMessage']}</p>
    {/if}
{/if}