<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Candy Framework for PHP</title>
    <link rel="shortcut icon" href="favicon.ico" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css">
    <style>
        /*
        * Globals
        */
        /* Links */
        
        a {
            color: #563B32;
        }
        
        a:focus,
        a:hover {
            color: #B2472C;
        }
        /* Custom default button */
        
        .btn-secondary,
        .btn-secondary:hover,
        .btn-secondary:focus,
        .btn-secondary:active,
        .btn-secondary:active:focus,
        .btn-secondary:active:hover {
            color: #563B32;
            text-shadow: none;
            /* Prevent inheritence from `body` */
            background-color: #fff;
            border: .05rem solid #563B32;
        }
        
        .btn-secondary:hover,
        .btn-secondary:focus {
            color: #B2472C;
            border-color: #B2472C;
        }
        /*
        * Base structure
        */
        
        html,
        body {
            height: 100%;
            font-family: "Helvetica Neue", Helvetica, Arial, "Hiragino Sans GB", "Hiragino Sans GB W3", "WenQuanYi Micro Hei", sans-serif;
            background: url("assets/img/bgrestaurant_icons.png");
        }
        
        body {
            color: #563B32;
            text-align: center;
        }
        /* Extra markup and styles for table-esque vertical and horizontal centering */
        
        .site-wrapper {
            display: table;
            width: 100%;
            height: 100%;
            /* For at least Firefox */
            min-height: 100%;
        }
        
        .site-wrapper-inner {
            display: table-cell;
            vertical-align: top;
        }
        
        .cover-container {
            margin-right: auto;
            margin-left: auto;
        }
        /* Padding for spacing */
        
        .inner {
            padding: 2rem;
        }
        /*
        * Header
        */
        
        .masthead {
            margin-bottom: 2rem;
        }
        
        .masthead-brand {
            margin-bottom: 0;
        }
        
        .nav-masthead .nav-link {
            padding: .15rem 0;
            color: #563B32;
            background-color: transparent;
            border-bottom: .05rem solid transparent;
        }
        
        .nav-masthead .nav-link+.nav-link {
            margin-left: 1rem;
        }
        
        .nav-masthead .nav-link:hover,
        .nav-masthead .active {
            color: #B2472C;
            border-bottom-color: #B2472C;
        }
        
        @media (min-width: 48em) {
            .masthead-brand {
                float: left;
            }
            .nav-masthead {
                float: right;
            }
        }
        /*
        * Cover
        */
        
        .cover {
            padding: 0 1.5rem;
        }
        
        .cover .btn-lg {
            padding: .75rem 1.25rem;
        }
        /*
        * Affix and center
        */
        
        @media (min-width: 40em) {
            /* Pull out the header and footer */
            .masthead {
                position: fixed;
                top: 0;
            }
            .mastfoot {
                position: fixed;
                bottom: 0;
            }
            /* Start the vertical centering */
            .site-wrapper-inner {
                vertical-align: middle;
            }
            /* Handle the widths */
            .masthead,
            .mastfoot,
            .cover-container {
                width: 100%;
                /* Must be percentage or pixels for horizontal alignment */
            }
        }
        
        @media (min-width: 72em) {
            .masthead,
            .mastfoot,
            .cover-container {
                width: 52rem;
            }
        }
    </style>
</head>

<body>
    <div class="site-wrapper">
        <div class="site-wrapper-inner">
            <div class="cover-container">
                <div class="masthead clearfix">
                    <div class="inner">
                        <img src="favicon.ico" style="margin-top: -0.35em; margin-right: 0.5em;float: left;" />
                        <h5 class="masthead-brand">Candy Framework</h5>
                        <nav class="nav nav-masthead">
                            <a class="nav-link" href="/">Home</a>
                            <a class="nav-link" href="https://github.com/Ult-UX/Candy-Framework/wiki" target="_blank">Manual</a>
                            <a class="nav-link" href="https://github.com/Ult-UX/Candy-Framework" target="_blank">Github</a>
                        </nav>
                    </div>
                </div>
                <div class="inner cover">
                    <h1 class="cover-heading">Welcome to Candy!</h1>
                    <p class="lead">This page you are looking at is being generated dynamically by Candy Framework.</p>
                    <p class="small">If you would like to edit this page you'll find it located at: <code>application/view/welcome_message.php</code></p>
                    <p class="small">The corresponding controller for this page is found at: <code>application/controller/WelcomeController.php</code></p>
                    <p class="lead">
                        <a href="#" class="btn btn-lg btn-secondary">Learn more</a>
                    </p>
                </div>
                <div class="mastfoot">
                    <div class="inner">
                        <p class="small">Page rendered in <strong><?php echo round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 6) ?></strong> seconds. Candy Framework Version <strong><?php echo CF_VERSION ?></strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery first, then Bootstrap JS. -->
    <script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js"></script>
</body>

</html>