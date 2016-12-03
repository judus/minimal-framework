<?php
    $title = isset($title) ? $title : '';

?><!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?=$title?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->

    <?=$this->asset->getCss()?>
    <?=$this->asset->getJs('top')?>
</head>
<body>
    <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser.
        Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve
        your experience and security.</p>
    <![endif]-->

    <!-- Add your site or application content here -->
    <p>Hello world! This is HTML5 Boilerplate. (Module)</p>

    <div class="content">
        <?= $this->yield() ?>
    </div>

    <?= $this->asset->getExternalJs('bottom') ?>
    <?= $this->asset->getInlineScripts('jQueryFallback') ?>
    <?= $this->asset->getJs('bottom') ?>
    <?= $this->asset->getInlineScripts() ?>


</body>
</html>