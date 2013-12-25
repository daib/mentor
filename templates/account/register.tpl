{include file='header.tpl' section='register'}

<link href="/css/signup.css" rel="stylesheet">

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h5 class="text-center">
                        SIGN UP</h5>
                    <form class="form form-signup" role="form" method="post" action="{geturl action='register'}" id="registration-form">
                        <div class="error"{if !$fp->hasError()} style="display: none"{/if}>
                            An error has occurred in the form below. Please check
                            the highlighted fields and re-submit the form.
                        </div>

                        <div class="form-group" id="form_username_container">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                <input type="text" class="form-control" placeholder="Username" id="form_username" name="username" value="{$fp->username|escape}" />
                                {include file='lib/error.tpl' error=$fp->getError('username')}
                            </div>
                        </div>
                        <div class="form-group" id="form_email_container">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span>
                                </span>
                                <input type="text" class="form-control" placeholder="Email Address" id="form_email" name="email" value="{$fp->email|escape}" />
                                {include file='lib/error.tpl' error=$fp->getError('email')}
                            </div>
                        </div>

                        <div id="form_first_name_container" class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                <input type="text" class="form-control" id="form_first_name" name="first_name" value="{$fp->first_name|escape}" placeholder="First Name" />
                                {include file='lib/error.tpl' error=$fp->getError('first_name')}
                            </div>
                        </div>

                        <div id="form_first_name_container" class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                <input type="text" class="form-control" id="form_last_name" name="last_name" value="{$fp->last_name|escape}" placeholder="Last Name" />
                                {include file='lib/error.tpl' error=$fp->getError('last_name')}
                            </div>
                        </div>

                        <div class="captcha">
                            <img src="/utility/captcha" alt="CAPTCHA image" />
                        </div>

                        <div class="form-group" id="form_captcha_container">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-question-sign"></span></span>
                                <input type="text" class="form-control" id="form_captcha" name="captcha" value="{$fp->captcha|escape}" placeholder="Enter Above Phrase"/>
                                {include file='lib/error.tpl' error=$fp->getError('captcha')}
                            </div>
                        </div>

                        <input type="submit" value="SUBMIT" class="btn btn-sm btn-primary btn-block" role="button"></input> 
                </form>
            </div>
        </div>
    </div>
</div>
</div> 


<script type="text/javascript" src="/js/UserRegistrationForm.class.js"></script>
<script type="text/javascript">
    new UserRegistrationForm('registration-form');
</script>

{include file='footer.tpl'}
