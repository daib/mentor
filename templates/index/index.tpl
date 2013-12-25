{include file='header.tpl' section='home'}
<div class="container">
    <h3>{$full_name}<br />
        <!--small>A simple, sheek navigation bar style!</small-->
    </h3>
    <br/>
    
    <div class="row">
        <div class="col-sm-2">
            <nav class="nav-sidebar">
                <ul class="nav">
                    <li class="active"><a href="/"><i class="glyphicon glyphicon-home"></i> Home</a></li>
                    <li><a href={geturl controller='blog'}{$blog_url}><i class="glyphicon glyphicon-briefcase"></i> Projects</a></li>
                    <li><a href={geturl controller='account' action='index'}><i class="glyphicon glyphicon-cog"></i> Setting</a></li>
                    <li class="nav-divider"></li>
                    {if $authenticated}
                        <li><a href="{geturl controller='account' action='logout'}"><i class="glyphicon glyphicon-off"></i> Sign out</a></li>
                    {else}
                        <li><a href="{geturl controller='account' action='login'}"><i class="glyphicon glyphicon-log-in"></i> Sign in</a></li>
                    {/if}
                    {if $relation_status eq 'owner'}
                    {elseif $relation_status eq 'friended'}
                        <li class="nav-divider"></li>
                        <button type="submit" uid={$uid} id="addfriend-btn" class="btn btn-blog-entry btn-default btn-sm"><i class="glyphicon glyphicon-user"></i> Friend</button>  
                    {elseif $relation_status eq 'requested'}
                        <li class="nav-divider"></li>
                        <button type="submit" uid={$uid} id="addfriend-btn" class="btn btn-blog-entry btn-default btn-sm"><i class="glyphicon glyphicon-user"></i> Friend requested</button>  
                    {else}
                        <li class="nav-divider"></li>
                        <button type="submit" uid={$uid} id="addfriend-btn" class="btn btn-blog-entry btn-default btn-sm"><i class="glyphicon glyphicon-user"></i> <span id="addfriend-btn-text">Add friend</span></button>  

                        {literal}
                        <script>
                        $(document).ready(function(){
                            $("#addfriend-btn").click(function(){
                            $.post("/account/friend", {"uid":$(this).attr("uid")})
                                .done(function( data ) {
                                    $("#addfriend-btn-text").text('Friend requested');
                                });
                            });
                        });
                        </script>
                        {/literal}
                    {/if}
                </ul>
            </nav>
        </div>

        <div class="col-sm-8 col-sm-offset-0">
            <nav class="nav-sidebar">
                <ul class="nav">
                    {foreach from=$items item=item}
                       <li> 
                            <h3>
                                <a href={$item.url}>{$item.title}</a>
                            </h3>
                            <button id="time-btn" class="btn btn-blog-entry btn-default btn-sm" value={$item.post_id}><i class="glyphicon glyphicon-time"></i> Created at {$item.ts}</button>
                            <button id="time-btn" class="btn btn-blog-entry btn-default btn-sm" value={$item.post_id}><i class="glyphicon glyphicon-user"></i> by {$item.user}</button>
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
