<!-- META DESCRIPTION / META TITLE -->
<?php
echo $this->Html->meta(['name' => 'robots', 'content' => 'noindex, follow'],null, ['inline'=>false]);
echo $this->Html->meta('description', '', ['inline' => false]);
$this->assign('page_title', 'Mot de passe oublié');
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
                    <?= $this->Form->create(null,
                        ['novalidate'=>true]
                    ); ?>

                    <?= $this->Form->control('email', ['label'=>'Adresse email']);?>

                </div>
            </div>


            <div class="row">
                <div class="small-6 columns">
                    <?= $this->Html->link('Retour',['action'=>'login','admin'=>true], ['class' => 'button secondary tiny radius']);?>
                </div>
                <div class="small-6 columns text-right">
                    <?= $this->Form->button('Nouveau mot de passe', ['type' => 'submit', 'class' => 'button tiny radius right']);?>
                </div>
            </div>

            <?= $this->Form->end(); ?>
        </div>
    </div>

</div>

