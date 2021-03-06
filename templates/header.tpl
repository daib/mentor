<!DOCTYPE html
   PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>{$title|escape}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="../../assets/ico/favicon.png">

        <script src="../../assets/js/jquery.js"></script>
        <script src="/js/bootstrap.min.js"></script>

    <!-- Bootstrap core CSS -->
        <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
        <link href="/css/jumbotron.css" rel="stylesheet">

        <link href="/css/mentor.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>
    <body>
    <div class="navbar navbar-mentor">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="http://mentor.com">Mentor</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li{if $section == 'home'} class="active"{/if}>
                    <a href="{geturl controller='index'}">Home</a>
                </li>

                {if $authenticated}
                    <li{if $section == 'account'} class="active"{/if}>
                        <a href="{geturl controller='account'}">Account</a>
                    </li>

                    <li{if $section == 'blog'} class="active"{/if}>
                      <a href="{geturl controller='blog'}">Blog</a>
                    </li>

                {else}
                    <li{if $section == 'register'} class="active"{/if}>
                        <a href="{geturl controller='account' action='register'}">Register</a>
                    </li>
                    <li{if $section == 'login'} class="active"{/if}>
                        <a href="{geturl controller='account' action='login'}">Login</a>
                    </li>
                {/if}

                <!--li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li class="dropdown-header">Nav header</li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li-->
          </ul>

        {if $authenticated}
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">{$identity->first_name|escape} {$identity->last_name|escape}<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li class="dropdown-header">Nav header</li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>

                <li>
                     <form class="navbar-form" method="post" action="{geturl controller='account' action='logout'}">
                        <button type="submit" class="btn btn-success">Sign out</button>  
                        <!--a href="{geturl controller='account' action='details'}">Update details</a-->.
                    </form>
                </li>

            </ul>

        {else}
                <form class="navbar-form navbar-right" method="post" action="{geturl controller='account' action='login'}">
                    <div class="form-group">
                        <input type="text" name="username" placeholder="Email" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" class="form-control">
                    </div>

                    <input type="hidden" name="redirect" value="/">
                    <button type="submit" class="btn btn-success">Sign in</button>  
                </form>

        {/if}

        
        </div><!--/.navbar-collapse -->
      </div>
    </div>

        <div id="content-container" class="column">
            <div id="content">
                <div id="breadcrumbs">
                    {breadcrumbs trail=$breadcrumbs->getTrail() separator=' &raquo; '}
                </div>

                <h1></h1>


