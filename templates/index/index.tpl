{include file='header.tpl' section='home'}

Blog of {$full_name} <br/>

<a href={geturl controller='blogmanager'}{$blog_url}>View blog of {$full_name}</a><br/>

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
