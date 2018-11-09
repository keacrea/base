<!-- META DESCRIPTION / META TITLE -->
<?php
echo $this->Html->meta(['name' => 'robots', 'content' => 'noindex, follow'],null, ['inline'=>false]);
echo $this->Html->meta('description', '', ['inline' => false]);
$this->assign('page_title', 'Espace d\'administration');
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
                    <?= $this->Form->create(null,[
                        'novalidate'=>true,
                        'autocomplete'=>'off']);?>

                    <?= $this->Form->control('email', ['label'=>'Adresse email', 'class' => 'radius']);?>
                    <?= $this->Form->control('password', ['label'=>'Mot de passe', 'class' => 'radius']);?>
                </div>
            </div>


            <div class="row">
                <div class="small-6 columns">
                    <div>
                        <?= $this->Form->checkbox('remember',['id'=>'remember']); ?>
                        <?= $this->Form->label('remember','Rester connecté');?>
                    </div>
                    <?= $this->Html->link('Mot de passe oublié', ['action'=>'forgot'], ['class' => 'left']);?>
                </div>
                <div class="small-6 columns text-right">
                    <?= $this->Form->button('Se connecter', ['type' => 'submit', 'class' => 'button tiny radius right']);?>
                </div>
            </div>

            <?= $this->Form->end(); ?>
        </div>
    </div>

</div>
