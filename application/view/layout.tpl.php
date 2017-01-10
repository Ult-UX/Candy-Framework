<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
        <block title>
            <?php echo $title; ?>
        </block>
    </title>
    <link href="//cdn.bootcss.com/foundation/6.3.0/css/foundation.min.css" rel="stylesheet">
    <link href="/public/libraries/foundation-icons/foundation-icons.css" rel="stylesheet" />
    <link href="/public/assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <div class="title-bar">
        <div class="row">
            <div class="title-bar-left">
                <span class="title-bar-title"><?php echo $title; ?></span>
            </div>
            <div class="title-bar-right" data-hide-for="medium">
                <span data-responsive-toggle="responsive-menu"><button class="menu-icon" type="button" data-toggle></button></span>
            </div>
            <div id="responsive-menu">
                <div class="title-bar-left">
                    <ul class="dropdown menu" data-dropdown-menu>
                        <li>
                            <a href="#">One</a>
                            <ul class="menu vertical">
                                <li><a href="#">One</a></li>
                                <li><a href="#">Two</a></li>
                                <li><a href="#">Three</a></li>
                            </ul>
                        </li>
                        <li><a href="/category/default.html">默认分类</a></li>
                        <li><a href="/articles.html">文章</a></li>
                    </ul>
                </div>
                <div class="title-bar-right">
                    <form action="/search.html" method="GET">
                        <ul class="menu">
                            <li><input type="search" name="keywords" placeholder="Search" required></li>
                            <li><button type="submit" class="button"><i class="fi-magnifying-glass"></i></button></li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <block container>
        <div>
        </div>
    </block>
    <footer>
        <div class='row'>
            <p>© <?php echo date('Y'); ?> ZURB, Inc. All rights reserved. <a href="/dashboard">Dashboard</a></p>
        </div>
    </footer>
    <script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/what-input/2.1.1/what-input.min.js"></script>
    <script src="//cdn.bootcss.com/foundation/6.3.0/foundation.min.js"></script>
    <script>
        $(document).foundation();
    </script>
    <script src="//cdn.bootcss.com/holder/2.9.4/holder.min.js"></script>
</body>

</html>