<?php
$this->assign('title', 'Ajouter une page');
$this->Breadcrumbs->add([
    ['title' => 'Gestion des contenus', 'url' => ['controller' => 'Pages', 'action' => 'index','prefix'=>'admin']],
    ['title' => 'Ajouter une page']
]);
?>

<?= $this->Form->create($page, ['novalidate' => true]); ?>
<div class="row">
    <div class="small-12 columns">
        <div class="portlet">
            <header class="row portlet__header">
                <div class="small-7 columns">
                    <h1 class="portlet__title">Ajouter une nouvelle page</h1>
                </div>
                <div class="small-5 columns text-right">
                    <?= $this->Form->button('Enregistrer et continuer', ['class' => 'button success tiny radius no-margin', 'div' => false]); ?>
                    <?= $this->Form->button('Enregistrer et fermer', ['name' => 'save_close', 'value' => true, 'class' => 'button secondary tiny radius no-margin']); ?>
                    <?= $this->Html->link('Fermer', $redirect = ($this->request->getSession()->read('Temp')) ? $this->request->getSession()->read('Temp') : ['action' => 'index'], ['class' => 'button tiny radius no-margin', 'title' => 'Fermer']); ?>
                </div>
            </header>
            <div class="content">
                <div class="row">
                    <div class="small-9 columns">
                        <?= $this->Form->control('type', ['type' => 'hidden', 'value' => $type]); ?>
                        <?= $this->Form->control('name', ['label' => 'Titre de la page']); ?>
                        <?= $this->Form->control('chapo', ['label' => 'Chapô de la page', 'class' => 'resume']); ?>

                        <br/>
                        <?= $this->Form->control('content', ['label' => 'Contenu de la page', 'class' => 'tiny']); ?>
                    </div>
                    <div class="small-3 columns">
                        <div class="checkbox">
                            <?= $this->Form->checkbox('online', ['label' => false, 'id' => 'online']); ?>
                            <?= $this->Form->label('online', 'Publier la page'); ?>
                        </div>
                        <div class="checkbox">
                            <?= $this->Form->checkbox('islink', ['label' => false, 'id' => 'islink']); ?>
                            <?= $this->Form->label('islink', 'Cette page sert de lien'); ?>
                        </div>
                        <?= $this->Form->control('menu_id', ['label' => 'Menu associé']); ?>

                        <div id="pageParent">
                            <?= $this->Form->control('parent_id', [
                                'options' => $parents,
                                'empty' => '---',
                                'label' => 'Page parente',
                                'escape' => false
                            ]); ?>
                        </div>

                        <?= $this->Form->control('slug', ['label' => 'url de la page (Doit être unique)']); ?>
                        <?= $this->Form->control('item', ['label' => 'Titre menu']); ?>
                        <?= $this->Form->control('meta_title', ['label' => 'Méta title (Prévoir environ 70 caractères)']); ?>
                        <?= $this->Form->control('meta_description', ['label' => 'Méta description (Prévoir environ 160 caractères)', 'type' => 'textarea', 'rows' => 2]); ?>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<?= $this->Form->end(); ?>


<div id="addModal" class="reveal-modal tiny" data-reveal></div>
