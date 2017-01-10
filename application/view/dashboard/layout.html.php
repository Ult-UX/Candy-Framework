<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@block(title)@ | Foundation Starter Template</title>
    <link href="//cdn.bootcss.com/foundation/6.3.0-rc1/foundation.min.css" rel="stylesheet" />
    <link href="/public/libraries/foundation-icons/foundation-icons.css" rel="stylesheet" />
    <link href="/public/libraries/layout_with_sidebar/layout.css" rel="stylesheet" />
</head>

<body class="layout-wrap">
    <div class="layout-wrap-inner">
        <div class="layout-sidebar layout-transition">
            <ul class="vertical menu" data-accordion-menu>
                <li><a href="/dashboard"><i class="fi-graph-pie"></i> OverView</a></li>
                <li>
                    <a href="#"><i class="fi-plus"></i> Quick Add</a>
                    <ul class="menu vertical nested">
                        <li><a href="/dashboard/content/article/add">New Post</a></li>
                        <li><a href="/dashboard/content/page/add">New Page</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#0">Contents</a>
                    <ul class="menu vertical nested">
                        <li><a href="#0">Item 2A</a></li>
                        <li><a href="#0">Item 2B</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#0">Item 1</a>
                    <ul class="menu vertical nested is-active">
                        <li>
                            <a href="#0">Item 1A</a>
                            <ul class="menu vertical nested">
                                <li><a href="#0">Item 1Ai</a></li>
                                <li><a href="#0">Item 1Aii</a></li>
                                <li><a href="#0">Item 1Aiii</a></li>
                            </ul>
                        </li>
                        <li><a href="#0">Item 1B</a></li>
                        <li><a href="#0">Item 1C</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="layout-container layout-transition">
            <div class="top-bar">
                <div class="top-bar-title">
                    <button class="menu-icon" type="button" data-toggle="layout-toggle"></button>
                    <strong>Spark MVC Dashboard</strong>
                </div>
                <div class="top-bar-left">
                </div>
                <div class="top-bar-right">
                    <ul class="menu">
                        <li><a href="/"><i class="fi-home"></i></a></li>
                        <li><button class="menu-icon" type="button"></button></li>
                    </ul>
                    <span data-responsive-toggle="responsive-menu" data-hide-for="medium">
                        <button class="menu-icon dark" type="button" data-toggle></button>
                    </span>
                </div>
            </div>
            @block(container)@
        </div>
    </div>
    <script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/what-input/2.1.1/what-input.min.js"></script>
    <script src="//cdn.bootcss.com/foundation/6.3.0-rc1/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
    <script src="/public/libraries/layout_with_sidebar/layout.js"></script>
</body>

</html>