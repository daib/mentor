            </div>
        </div>

        <!--div id="left-container" class="column">
            <div class="box">
                Left column placeholder
            </div>
        </div>

        <div id="right-container" class="column">
            {if $messages|@count > 0}
                <div id="messages" class="box">
                    {if $messages|@count == 1}
                        <strong>Status Message:</strong>
                        {$messages.0|escape}
                    {else}
                        <strong>Status Messages:</strong>
                        <ul>
                            {foreach from=$messages item=row}
                                <li>{$row|escape}</li>
                            {/foreach}
                        </ul>
                    {/if}
                </div>
            {else}
                <div id="messages" class="box" style="display:none"></div>
            {/if}

            <div class="box">
                {if $authenticated}
                    Logged in as
                    {$identity->first_name|escape} {$identity->last_name|escape}
                    (<a href="{geturl controller='account' action='logout'}">logout</a>).
                    <a href="{geturl controller='account' action='details'}">Update details</a>.
                {else}
                    You are not logged in.
                    <a href="{geturl controller='account' action='login'}">Log in</a> or
                    <a href="{geturl controller='account' action='register'}">register</a> now.
                {/if}
            </div>
        </div-->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    </body>
</html>
