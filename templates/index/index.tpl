{include file='header.tpl' section='home'}
<div class="container">
    <h3>{$full_name}<br />
        <small>A simple, sheek navigation bar style!</small>
    </h3>
    <br />
    
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

                </ul>
            </nav>
        </div>
    </div>
</div>


{if $relation_status eq 'owner'}
{elseif $relation_status eq 'friended'}
    <button type="submit" uid={$uid} id="addfriend-btn" class="btn btn-success">Friend</button>  
{elseif $relation_status eq 'requested'}
    <button type="submit" uid={$uid} id="addfriend-btn" class="btn btn-success">Friend requested</button>  
{else}
    <button type="submit" uid={$uid} id="addfriend-btn" class="btn btn-success">Add friend</button>  

    {literal}
    <script>
    $(document).ready(function(){
        $("#addfriend-btn").click(function(){
        $.post("/account/friend", {"uid":$(this).attr("uid")})
            .done(function( data ) {
                $("#addfriend-btn").text('Friend requeted');
            });
        });
    });
    </script>
    {/literal}

{/if}


{include file='footer.tpl'}
