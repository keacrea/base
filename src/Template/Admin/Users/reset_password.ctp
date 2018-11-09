
<!-- META DESCRIPTION / META TITLE -->
<?php
echo $this->Html->meta(['name' => 'robots', 'content' => 'noindex, follow'],null, ['inline'=>false]);
echo $this->Html->meta('description', '', ['inline' => false]);
$this->assign('page_title', 'Nouveau mot de passe');
?>
<!-- META DESCRIPTION / META TITLE END -->
<div class="row">
    <div class="large-12 columns">
        <div class="portlet portlet__connection">
            <header class="row portlet__header">
                <div class="small-12 columns">
                    <div class="portlet__title">
                        Connexion espace administrateur
                    </div>
                </div>
            </header>

            <div class="row">
                <div class="small-12 columns">
                    <?= $this->Flash->render('auth') ?>
                    <?= $this->Form->create(null,['novalidate'=>true,'autocomplete'=>'off']); ?>

                    <?= $this->Form->control('password', ['label'=>'Nouveau mot de passe']);?>
                    <?= $this->Form->control('confirm_password', ['label'=>'Confirmer votre mot de passe','type'=>'password']);?>
                </div>
            </div>


            <div class="row">
                <div class="small-6 columns">
                    <?= $this->Html->link('Espace connexion',['action'=>'login','admin'=>true], ['class' => 'button secondary tiny radius','autocomplete'=>'off']);?>
                </div>
                <div class="small-6 columns text-right">
                    <?= $this->Form->button('Valider', ['type' => 'submit', 'class' => 'button tiny radius right','autocomplete'=>'off']);?>
                </div>
            </div>

            <?= $this->Form->end(); ?>
        </div>
    </div>

</div>

