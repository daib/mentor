{include file='header.tpl' section='blog'}

<div class="container">
    <h3>Projects<br />
    </h3>
    <br />
    
    <div class="row">
        <div class="col-sm-2">
            <nav class="nav-sidebar">
                <ul class="nav">
                    <li><a href="/"><i class="glyphicon glyphicon-home"></i> Home</a></li>
                    <li class="active"><a href={geturl controller='blog'}{$blog_url}><i class="glyphicon glyphicon-briefcase"></i> Projects</a></li>
                    <li><a href={geturl controller='account' action='index'}><i class="glyphicon glyphicon-cog"></i> Setting</a></li>
                    <li class="nav-divider"></li>
                    {if $authenticated}
                        <li><a href="{geturl controller='account' action='logout'}"><i class="glyphicon glyphicon-off"></i> Sign out</a></li>
                    {else}
                        <li><a href="{geturl controller='account' action='login'}"><i class="glyphicon glyphicon-log-in"></i> Sign in</a></li>
                    {/if}

                </ul>
            </nav>
        </div>

        <div class="col-sm-8 col-sm-offset-0">
            <nav class="nav-sidebar">
                <ul class="nav">
                    {if $isowner}
                    <form method="get" action="{geturl action='edit'}">
                        <div class="submit">
                            <input type="submit" value="Create new blog post" />
                        </div>
                    </form>
                    {/if}
                    {foreach from=$items item=item}
                       <li> 
                            <h3>
                                <a href={$item.url}>{$item.title}</a>
                            </h3>
                            <button id="like-btn" class="btn btn-blog-entry btn-default btn-sm" value={$item.post_id}><i class="glyphicon glyphicon-time"></i> Created at {$item.ts}</button>
                            <br/>
                            {$item.content}

                        </li>

                        <button id="like-btn" class="btn btn-blog-entry btn-default btn-sm" value={$item.post_id}><i class="glyphicon glyphicon-thumbs-up"></i> Like</button>
                        <button id="comment-btn" class="btn btn-blog-entry btn-default btn-sm" value={$item.post_id}><i class="glyphicon glyphicon-comment"></i> Comment</button>
                        <button id="share-btn" class="btn btn-blog-entry btn-default btn-sm" value={$item.post_id} ><i class="glyphicon glyphicon-share"></i> Share</button>
                        <button id="add-btn" class="btn btn-blog-entry btn-default btn-sm" value={$item.post_id} ><i class="glyphicon glyphicon-user"></i> Add</button>
                        <button id="edit-btn" class="btn btn-blog-entry btn-default btn-sm" value={$item.post_id} ><i class="glyphicon glyphicon-edit"></i> Edit</button>
                        <button id="delete-btn" class="btn btn-blog-entry btn-default btn-sm" value={$item.post_id} ><i class="glyphicon glyphicon-remove"></i> Delete</button>
                       <li class="nav-divider"></li>
                    {/foreach}
                </ul>
            </nav>
        </div>

    </div>
</div>

{include file='footer.tpl'}
