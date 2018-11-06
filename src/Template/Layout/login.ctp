<!DOCTYPE html>
<html class="no-js" lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="msapplication-config" content="none"/>
    <title>
        <?php echo $this->fetch('page_title');?>
    </title>
	<?= $this->element('favicon');?>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('admin/app');
        echo $this->fetch('css');
		echo $this->fetch('meta');

        echo $this->Html->script('admin/modernizr.js');
	?>
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/favicon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicon/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/favicon/manifest.json">
    <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="theme-color" content="#ffffff">
</head>
<body class="login__body">

    <header class="topbar">
        <div class="row full">
            <div class="small-6 columns">
                <h1 class="topbar__title">Espace administration</h1>
            </div>
        </div>
    </header>

    <div class="wrap wrap-content wrap-login">
        <div class="row collapse">
            <div class="small-12 large-6 small-centered columns">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content'); ?>
            </div>
        </div>
    </div>


    <?php
    echo $this->Html->script('/bower_components/jquery/dist/jquery.min.js');
    echo $this->Html->script('admin/fastclick.js');
    echo $this->Html->script('admin/foundation.min.js');

    echo $this->Html->script('admin/login.js');
    echo $this->fetch('script');
    ?>
</body>
</html>
