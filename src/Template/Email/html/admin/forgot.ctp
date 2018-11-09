<?php
use Cake\Routing\Router;
$url = Router::url(
    [
        'controller' => 'Users',
        'action' => 'resetPassword',
        $token
    ],
    true
);
?>
<html>
<head>
    <title><?= $this->fetch('title') ?></title>
</head>
<body>
<?= $this->fetch('content') ?>
<h3>Bonjour</h3>
<p>
    Vous avez demandé à renouveler votre mot de passe.
</p>
<p>
    Pour cela merci de cliquer sur <?= $this->Html->link('ce lien', $url) ?>
</p>
<p>
    Si vous n'avez effectué aucune demande, merci d'ignorer ce message.
</p>
</body>
</html>
