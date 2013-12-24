{include file='header.tpl' section='blog'}

<script type="text/javascript" src="/js/blogPreview.js"></script>

<div class="preview-date">
    {$post->ts_created|date_format:'%x %X'}
</div>

<div class="preview-content">
    {$post->profile->content}
</div>

<div id="comments-box-group">
</div>

Comments:<br/>
<textarea name="comment" id="comment">
</textarea><br />
<button type="submit" id="commentsubmit-btn" class="btn btn-default">Submit</button>
<input type="hidden" value="{$post->getId()}" id="post_id"/>
{literal}
<script>
$(document).ready(function(){
    $("#commentsubmit-btn").click(function(){
        $.post("/blog/comment", {"comment":$("#comment").val(), "post_id":$("#post_id").val()})
            .done(function( data ) {
                var result = $.parseJSON(data);
                if(result['comment_id'] > 0) {
                    $("#comments-box-group").append('<div id=' + result['comment_id'] + '><p>' + $('#comment').val() + '<br/> Created at ' + result['ts'] + "</p></div>");
                }
            });
        });
    });
</script>
{/literal}


<form method="post"
      action="{geturl controller='blog' action='setstatus'}"
      id="status-form">

    <div class="preview-status">
        <input type="hidden" name="id" value="{$post->getId()}" />
        {if $post->isLive()}
            <div class="status live">
                This post is live on your blog. To unpublish
                it click the <strong>Unpublish post</strong> button below.
                <div>
                    <input type="submit" value="Unpublish post"
                           name="unpublish" id="status-unpublish" class="btn btn-success"/>
                    <input type="submit" value="Edit post"
                           name="edit" id="status-edit" class="btn btn-success"/>
                    <input type="submit" value="Delete post"
                           name="delete" id="status-delete" class="btn btn-success"/>
                </div>
            </div>
        {else}
            <div class="status draft">
                This post is not yet live on your blog. To publish
                it on your blog, click the button below.
                <div>
                    <input type="submit" value="Publish post"
                           name="publish" id="status-publish" class="btn btn-success"/>
                    <input type="submit" value="Edit post"
                           name="edit" id="status-edit" class="btn btn-success" />
                    <input type="submit" value="Delete post"
                           name="delete" id="status-delete" class="btn btn-success" />
                </div>
            </div>
        {/if}
    </div>

</form>


{include file='footer.tpl'}
