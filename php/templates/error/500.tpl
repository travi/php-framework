<h2>Internal Server Error</h2>
<p>An Error Has Occurred While Processing Your Request</p>
{*{$content['type']}*}
{*{if !empty($content['message'])}*}
{*<p>{$content['message']}</p>*}
{*{/if}*}


{*{if !empty($content['message'])}*}
{*<ol>*}
    {*{foreach item=line from=$content['trace']}*}
    {*<li>{$line['file']}({$line['line']}): {if !empty($line['class'])}*}
        {*{$line['class']}{$line['type']}*}
    {*{/if}{$line['function']}({if !empty($line['args'])}{foreach item=arg from=$line['args'] name="args"}{$arg}{if !$smarty.foreach.args.last},&nbsp{/if}{/foreach}{/if})</li>*}
    {*{/foreach}*}
{*</ol>*}
{*{/if}*}