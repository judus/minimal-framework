<?php
$title = isset($title) ? $title : '';

?><!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= $title ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->

    <?= $this->assets->getCss() ?>
    <?= $this->assets->getJs('top') ?>
</head>
<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser.
    Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve
    your experience.</p>
<![endif]-->
<nav class="navbar navbar-static-top navbar-dark bg-inverse">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="http://bootbites.com">Minimal</a>
        </div>
        <button class="navbar-toggler hidden-xs-up pull-xs-right" type="button"
                data-toggle="collapse" data-target="#exCollapsingNavbar">
            &#9776;
        </button>
        <div class="collapse navbar-toggleable-sm hidden-xs-up" id="exCollapsingNavbar">
            <ul class="nav navbar-nav pull-sm-right">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?= $this->yield() ?>


<div class="container">
    <hr>
    <!-- Example row of columns -->
    <div class="row">
        <div class="col-md-4">
            <h2>Heading</h2>
            <p>Donec id elit non mi porta gravida at eget metus. Fusce
                dapibus, tellus ac cursus commodo, tortor mauris condimentum
                nibh, ut fermentum massa justo sit amet risus. Etiam porta
                sem malesuada magna mollis euismod. Donec sed odio dui. </p>
            <p><a class="btn btn-primary" href="#" role="button">View
                    details &raquo;</a></p>
        </div>
        <div class="col-md-4">
            <h2>Heading</h2>
            <p>Donec id elit non mi porta gravida at eget metus. Fusce
                dapibus, tellus ac cursus commodo, tortor mauris condimentum
                nibh, ut fermentum massa justo sit amet risus. Etiam porta
                sem malesuada magna mollis euismod. Donec sed odio dui. </p>
            <p><a class="btn btn-primary" href="#" role="button">View
                    details &raquo;</a></p>
        </div>
        <div class="col-md-4">
            <h2>Heading</h2>
            <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in,
                egestas eget quam. Vestibulum id ligula porta felis euismod
                semper. Fusce dapibus, tellus ac cursus commodo, tortor
                mauris condimentum nibh, ut fermentum massa justo sit amet
                risus.</p>
            <p><a class="btn btn-primary" href="#" role="button">View
                    details &raquo;</a></p>
        </div>
    </div>
</div>


<div class="container">
    <hr>
    <footer>
        <p>&copy; Acme 2016</p>
    </footer>
</div>

<?= $this->assets->getExternalJs('bottom') ?>
<?= $this->assets->getInlineScripts('jQueryFallback') ?>
<?= $this->assets->getJs('bottom') ?>
<?= $this->assets->getInlineScripts() ?>

</body>
</html>