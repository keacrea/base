<?php

/**
 * @var $this
 */

$adminTemplates = [
    'checkboxWrapper' => '{{label}}',
];
$this->Form->setTemplates($adminTemplates);
?>

<!DOCTYPE html>
<!--[if IE 9]>
<html class="lt-ie10" lang="fr"> <![endif]-->
<html class="no-js" lang="fr">
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="msapplication-config" content="none"/>
    <title>
        <?php echo $this->fetch('title');?>

    </title>
    <?= $this->element('favicon');?>
    <?php
    echo $this->Html->meta('icon');
    echo $this->Html->css('/bower_components/fancybox/source/jquery.fancybox.css');
    echo $this->Html->css('/bower_components/datetimepicker/build/jquery.datetimepicker.min.css');
    echo $this->Html->css('/bower_components/select2/dist/css/select2.min.css');
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
<body class="admin">
<header class="topbar">
    <div class="row full">
        <div class="small-6 columns">
            <h1 class="topbar__title">Espace administration</h1>
        </div>
        <div class="small-6 columns text-right">

            <ul class="button-group radius topbar__action">
                <li>
                    <?php
                    echo $this->Html->link(
                        '<i class="fa fa-sign-out"></i> Voir le site',
                        '/',
                        [
                            'class'  => 'topbar__btn button secondary tiny',
                            'target' => '_blank',
                            'escape' => false,
                            'title'  => 'Voir le site'
                        ]
                    );
                    ?>
                </li>
                <li>
                    <?php
                    echo $this->Html->link(
                        '<i class="fa fa-power-off"></i> Déconnexion',
                        [
                            'controller' => 'Users',
                            'action'     => 'logout',
                            'prefix'      => 'admin'
                        ],
                        [
                            'class'  => 'topbar__btn button tiny',
                            'escape' => false,
                            'title'  => 'Déconnexion'
                        ]
                    );
                    ?>
                </li>
            </ul>
        </div>
    </div>
</header>

<div class="wrap wrap-content">
    <div class="row full collapse wrap-content">
        <div class="small-2 columns sidebar">
            <nav class="navigation">
                <ul class="navigation__list">

                    <li>
                        <?= $this->Html->link(
                            '<i class="fa fa-sitemap"></i> Gestion des pages',
                            [
                                'controller' => 'Pages',
                                'action'     => 'index',
                                'prefix'      => 'admin'
                            ],
                            [
                                'escape' => false,
                                'class'  => (in_array($this->request->getParam('controller'),['Pages'])) ? 'active' : ''
                            ]);?>
                        <ul class="navigation__sublist">
                            <li>
                                <?= $this->Html->link(
                                    'Menu principal',
                                    [
                                        'controller' => 'Pages',
                                        'action'     => 'index',
                                        'prefix'      => 'admin',
                                        '?'=>['menu_id'=>1]
                                    ],
                                    [
                                        'escape' => false,
                                        'class'  => (in_array($this->request->getParam('controller'),['Pages']) && $this->request->getQuery('menu_id') == 1) ? 'active' : ''
                                    ]);?>
                            </li>
                            <li>
                                <?= $this->Html->link(
                                    'Menu footer',
                                    [
                                        'controller' => 'Pages',
                                        'action'     => 'index',
                                        'prefix'      => 'admin',
                                        '?'=>['menu_id'=>2]
                                    ],
                                    [
                                        'escape' => false,
                                        'class'  => (in_array($this->request->getParam('controller'),['Pages']) && $this->request->getQuery('menu_id') == 2) ? 'active' : ''
                                    ]);?>
                            </li>
                        </ul>
                    </li>


                    <li>
                        <?= $this->Html->link(
                            '<i class="fa fa-envelope-o"></i> Demandes reçues',
                            '#',
                            [
                                'escape' => false,
                                'class'  => (in_array($this->request->getParam('controller'),['Contacts', 'Demos'])) ? 'active' : ''
                            ]);?>
                        <ul class="navigation__sublist">
                            <li>
                                <?= $this->Html->link(
                                    'Demandes de contact',
                                    [
                                        'controller' => 'Contacts',
                                        'action'     => 'index',
                                        'prefix'      => 'admin'
                                    ],
                                    [
                                        'escape' => false,
                                        'class'  => (in_array($this->request->getParam('controller'),['Contacts'])) ? 'active' : ''
                                    ]);?>
                            </li>
                        </ul>
                    </li>



                    <li>
                        <?= $this->Html->link(
                            '<i class="fa fa-gears"></i> Paramètres du site',
                            [
                                'controller' => 'SettingSites',
                                'action'     => 'edit',
                                'prefix'      => 'admin'
                            ],
                            [
                                'escape' => false,
                                'class'  => ($this->request->getParam('controller') == 'SettingSites') ? 'active' : ''
                            ]
                        );
                        ?>
                    </li>

                    <li>
                        <?= $this->Html->link(
                            '<i class="fa  fa-user-secret"></i> Gestion administrateur',
                            [
                                'controller' => 'Users',
                                'action'     => 'index',
                                'prefix'      => 'admin'
                            ],
                            [
                                'escape' => false,
                                'class'  => ($this->request->getParam('controller') == 'Users') ? 'active' : ''
                            ]
                        );
                        ?>
                    </li>
                </ul>
            </nav>

        </div>

        <div class="small-10 columns main-content">

            <div class="row">
                <div class="small-12 columns">
                    <?php
                    $this->Breadcrumbs->prepend(
                        'Accueil',
                        ['controller' => 'Users', 'action' => 'index', 'prefix' => 'admin']
                    );
                    echo $this->Breadcrumbs->render(
                        [
                            'class' => 'breadcrumbs',
                            'lastClass' => 'current'
                        ]
                    );
                    ?>

                    <?= $this->Flash->render() ?>
                </div>
            </div>

            <?= $this->fetch('content'); ?>
        </div>
    </div>
</div>
<div id="token" data-token="<?= $this->request->getParam('_csrfToken');?>"></div>
<div id="tiny" data-tiny="<?= md5(\Cake\Utility\Security::getSalt());?>"></div>


<?php
echo $this->Html->script('admin/jquery.min.js');
echo $this->Html->script('admin/jquery-ui.min.js');
echo $this->Html->script('admin/fastclick.js');
echo $this->Html->script('admin/foundation.min.js');
echo $this->Html->script('/bower_components/fancybox/source/jquery.fancybox.js');
echo $this->Html->script('/bower_components/datetimepicker/build/jquery.datetimepicker.full.min.js');
echo $this->Html->script('/bower_components/select2/dist/js/select2.full.min');
echo $this->Html->script('/bower_components/select2/dist/js/i18n/fr');
echo $this->Html->script('admin/tinymce/tinymce.min.js');

echo $this->Html->script('admin/tiny.js');
echo $this->Html->script('admin/navigation.js');
echo $this->Html->script('admin/select_docs.js');
echo $this->Html->script('admin/app.js');
echo $this->fetch('script');
?>
<script src="https://use.fontawesome.com/025243e9d3.js"></script>
</body>
</html>
