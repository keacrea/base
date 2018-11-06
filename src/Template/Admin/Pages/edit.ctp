<?php
$this->assign('title', 'Modifier la page ' . $page->item);
$this->Breadcrumbs->add([
    ['title' => 'Gestion des contenus', 'url' => ['controller' => 'Pages', 'action' => 'index', 'prefix' => 'admin']],
    ['title' => 'Modifier la page ' . $page->item]
]);
?>

<?php echo $this->Form->create($page, ['novalidate' => true]); ?>

<div class="row">
    <div class="small-12 columns">
        <div class="portlet">
            <header class="row portlet__header">
                <div class="small-7 columns">
                    <h1 class="portlet__title">Modifier la page <?= $page->item; ?></h1>
                </div>
                <div class="small-5 columns text-right">
                    <?= $this->Html->link('Prévisualiser', $page->url, ['target' => '_blank', 'class' => 'button tiny radius no-margin', 'div' => false]); ?>
                    <?= $this->Form->button('Enregistrer et continuer', ['class' => 'button success tiny radius no-margin', 'div' => false]); ?>
                    <?= $this->Form->button('Enregistrer et fermer', ['name' => 'save_close', 'value' => true, 'class' => 'button secondary tiny radius no-margin']); ?>
                    <?php if (in_array($page->type, ['presentation'])): ?>
                    <?php echo $this->Html->link('Fermer', ['controller' => 'Pages',  'action' => 'index'], ['class' => 'button tiny radius no-margin', 'title' => 'Fermer']); ?>
                    <?php else:?>
                        <?php echo $this->Html->link('Fermer', $redirect = ($this->request->getSession()->read('Temp')) ? $this->request->getSession()->read('Temp') : ['action' => 'index'], ['class' => 'button tiny radius no-margin', 'title' => 'Fermer']); ?>
                    <?php endif; ?>
                </div>
            </header>
            <div class="row">
                <div class="small-12 columns">
                    <dl class="tabs">

                        <dd class="active tabs-menu"><a
                                href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'edit', $page->id]) ?>">Général</a>
                        </dd>
                        <?php if (in_array($page->type, ['presentation'])): ?>
                            <dd class="tabs-menu"><a
                                    href="<?= $this->Url->build(['controller' => 'Teams', 'action' => 'index', $page->id]) ?>">Gestion de l'équipe</a></dd>
                        <?php endif; ?>

                    </dl>
                    <div class="tabs-content">
                        <div class="content active tabs-bloc" id="formation">
                            <div class="row">
                                <div class="small-9 columns">
                                    <?= $this->Form->control('id'); ?>

                                    <?= $this->Form->control('name', ['label' => 'Titre de la page']); ?>

                                    <?php if (!in_array($page->type, ['form', 'steps'])): ?>
                                        <?= $this->Form->control('chapo', ['label' => 'Chapô de la page', 'class' => 'resume']); ?>
                                    <?php endif; ?>

                                    <?php if (!in_array($page->type, ['home', 'static'])): ?>
                                    <?= $this->Form->control('content', ['label' => 'Contenu de la page', 'class' => 'tiny']); ?>
                                    <?php endif; ?>
                                </div>
                                <div class="small-3 columns">

                                    <?php if (!in_array($page->type, ['home', 'confirm', 'form'])): ?>
                                        <div class="checkbox">
                                            <?= $this->Form->checkbox('online', ['label' => false, 'id' => 'online']); ?>
                                            <?= $this->Form->label('online', 'Publier la page'); ?>
                                        </div>
                                        <div class="checkbox">
                                            <?= $this->Form->checkbox('islink', ['label' => false, 'id' => 'islink']); ?>
                                            <?= $this->Form->label('islink', 'Cette page sert de lien'); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!in_array($page->type, ['home', 'form', 'confirm', 'static'])): ?>


                                        <?= $this->Form->control('menu_id', ['empty' => '---', 'label' => 'Menu associé']); ?>

                                        <?php $style = ($page->menu_id != 1) ? 'display:none' : ''; ?>
                                        <div id="pageParent" style="<?php echo $style; ?>">
                                            <?= $this->Form->control('parent_id', [
                                                'options' => $parents,
                                                'empty' => '---',
                                                'label' => 'Page parente',
                                                'escape' => false]); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!in_array($page->type, ['home', 'confirm', 'form', 'static'])): ?>
                                        <?= $this->Form->control('slug', ['label' => 'url de la page (Doit être unique)']); ?>
                                    <?php endif; ?>

                                    <?= $this->Form->control('item', ['label' => 'Titre menu']); ?>


                                    <?= $this->Form->control('meta_title', ['label' => 'Méta title (Prévoir environ 70 caractères)']); ?>
                                    <?= $this->Form->control('meta_description', ['label' => 'Méta description (Prévoir environ 160 caractères)', 'type' => 'textarea', 'rows' => 2]); ?>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>
<div id="delImg" class="reveal-modal tiny" data-reveal></div>
