<p class="lead">Bonjour,</p>
<p>Vous avez demandé à renouveler votre mot de passe.<br/>
    Pour cela merci de cliquer sur le lien suivant : <?= $this->Html->link('Nouveau mot de passe',['controller'=>'Users','action'=>'resetPassword','prefix'=>'admin',$id,$token,'_full' => true]);?> et de suivre la procédure indiquée.</p>
