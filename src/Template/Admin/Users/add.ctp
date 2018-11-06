<?php $this->assign('title','Ajouter un administrateur'); ?>

<?php
$this->Breadcrumbs->add([
['title' => 'Gestion des administrateurs', 'url' => ['controller' => 'Users', 'action' => 'index','prefix'=>'admin']],
['title' => 'Ajouter un administrateur']
]);
?>

<?= $this->Form->create($user,['novalidate'=>true]); ?>
<div class="row">
    <div class="small-12 columns">
        <div class="portlet">
            <div class="row portlet__header">
                <div class="small-8 columns">
                    <h1 class="portlet__title">Ajouter un administrateur</h1>
                </div>
                <div class="small-4 columns text-right">
                    <?= $this->Form->button('Enregistrer', ['class'=>'button success tiny radius no-margin','div'=>false]);?>
                </div>
            </div>
            <div class="content">
                <div class="row">
                    <div class="small-8 columns">
                        <?= $this->Form->control('name', ['label'=>'Nom']);?>
                        <?= $this->Form->control('mail', ['label'=>'Adresse email']);?>
                        <?= $this->Form->control('password', ['label'=>'Nouveau mot de passe','value'=>'','autocomplete'=>'off']);?>
                        <?= $this->Form->control('confirm_password', ['label'=>'Confirmer votre mot de passe','value'=>'','type'=>'password']);?>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->Form->end();?>
