<?php $this->assign('title','Modifier un administrateur'); ?>
<?php $this->Html->addCrumb('Gestion des administrateurs', ['action' => 'index','prefix'=>'admin']);?>
<?php $this->Html->addCrumb('Modifier un administrateur');?>

<?= $this->Form->create($user,['novalidate'=>true]); ?>
<div class="row">
    <div class="small-12 columns">
        <div class="portlet">
            <div class="row portlet__header">
                <div class="small-8 columns">
                    <h1 class="portlet__title">Modifier un administrateur</h1>
                </div>
                <div class="small-4 columns text-right">
                    <?= $this->Form->button('Mettre à jour le profil', ['class'=>'button success tiny radius no-margin','div'=>false]);?>
                </div>
            </div>
            <div class="content">
                <div class="row">
                    <div class="small-8 columns">
                        <?= $this->Form->control('id');?>
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
