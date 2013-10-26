{include file='header.tpl' section='blogmanager'}

{if $isowner}
<form method="get" action="{geturl action='edit'}">
    <div class="submit">
        <input type="submit" value="Create new blog post" />
    </div>
</form>
{/if}
<ul>
    {foreach from=$items item=item}
       <li><a href={$item.url}>{$item.title}</a></li>
    {/foreach}
</ul>
{include file='footer.tpl'}
