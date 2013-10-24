{include file='header.tpl' section='home'}

Blog of {$full_name} <br/>

<a href={geturl controller='blogmanager'}{$blog_url}>View blog of {$full_name}</a><br/>

<button type="submit" uid={$uid} id="addfriend-btn" class="btn btn-success">Add friend</button>  

{literal}
<script>
$(document).ready(function(){
  $("#addfriend-btn").click(function(){
    $.get("/account/friend", $(this).attr("uid"));
  });
});
</script>
{/literal}


{include file='footer.tpl'}
