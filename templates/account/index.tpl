{include file='header.tpl' section='account'}

Welcome {$identity->first_name}.

<ul>
    <li><a href="{geturl controller='blog'}">View all blog posts</a></li>
    <li><a href="{geturl controller='blog'
                         action='edit'}">Post new blog entry</a></li>
</ul>

{include file='footer.tpl'}
