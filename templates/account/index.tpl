{include file='header.tpl' section='account'}

<div class="container">
    <h3>Change your settings<br />
    </h3>
    <br />
    
    <div class="row">
        <div class="col-sm-2">
            <nav class="nav-sidebar">
                <ul class="nav">
                    <li><a href="/"><i class="glyphicon glyphicon-home"></i> Home</a></li>
                    <li><a href={geturl controller='blog' action='index'}"><i class="glyphicon glyphicon-briefcase"></i> Projects</a></li>
                    <li class="active"><a href={geturl controller='account' action='index'}><i class="glyphicon glyphicon-cog"></i> Setting</a></li>
                    <li class="nav-divider"></li>
                    {if $authenticated}
                        <li><a href="{geturl controller='account' action='logout'}"><i class="glyphicon glyphicon-off"></i> Sign out</a></li>
                    {else}
                        <li><a href="{geturl controller='account' action='login'}"><i class="glyphicon glyphicon-log-in"></i> Sign in</a></li>
                    {/if}

                </ul>
            </nav>
        </div>

    </div>
</div>


<!--ul>
    <li><a href="{geturl controller='blog'}">View all blog posts</a></li>
    <li><a href="{geturl controller='blog'
                         action='edit'}">Post new blog entry</a></li>
</ul-->

{include file='footer.tpl'}
