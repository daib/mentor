{include file='header.tpl' section='blogmanager'}

<form method="get" action="{geturl action='edit'}">
    <div class="submit">
        <input type="submit" value="Create new blog post" />
    </div>
</form>
<ul>
    {foreach from=$post_ids item=entry}
       <li>{$entry}</li>
    {/foreach}
</ul>
{include file='footer.tpl'}
