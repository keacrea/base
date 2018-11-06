<?php $this->assign('title', 'Modifier les paramètres'); ?>
<?php $this->Breadcrumbs->add('Modifier les paramètres'); ?>

<?= $this->Form->create($settingSite, ['novalidate' => true]); ?>
<div class="row">
    <div class="small-12 columns">
        <div class="portlet">
            <header class="row portlet__header">
                <div class="small-7 columns">
                    <h1 class="portlet__title">Modifier les paramètres</h1>
                </div>
                <div class="small-5 columns text-right">
                    <?= $this->Form->button('Enregistrer', ['class' => 'button success tiny radius no-margin', 'div' => false]); ?>
                </div>
            </header>
            <div class="row">
                <div class="small-8 columns">
                    <?= $this->Form->control('id'); ?>

                    <div class="panel callout">
                        <h4>Coodonnées</h4>
                        <div class="row">
                            <?= $this->Form->control('email', ['label' => 'Adresse email']); ?>
                            <?= $this->Form->control('phone', ['label' => 'Numéro de téléphone']); ?>
                            <?= $this->Form->control('address', ['label' => 'Adresse']); ?>
                            <?= $this->Form->control('zipcode', ['label' => 'Code postal']); ?>
                            <?= $this->Form->control('city', ['label' => 'Ville']); ?>
                        </div>
                    </div>

                </div>
                <div class="small-4 columns">
                    <div class="row collapse">
                        <div class="small-12 columns">
                            <label for="">Logo : dimension de 207px par 56px</label>
                        </div>
                        <div class="small-8 columns">
                            <?= $this->Form->control('logo', ['class' => 'upload__input_second_logo', 'label' => false, 'div' => false, 'readonly' => true]); ?>
                        </div>
                        <div class="small-4 columns">
                            <?= $this->Html->link('Sélectionner', '/filemanager/dialog.php?type=1&field_id=logo&akey=' . md5(\Cake\Utility\Security::getSalt()), ['class' => 'upload__btn lightbox select-filemanager']); ?>
                        </div>
                    </div>

                    <?php if ($settingSite->logo): ?>
                        <div id="img_logo" class="upload__preview clearfix">
                            <div class="upload__wrap-img-preview">
                                <?= $this->Image->resizeConstraint($settingSite->logo, 120, 120, ['class' => 'img-preview upload__img-preview']); ?>
                            </div>
                        </div>

                    <?php endif; ?>


                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end(); ?>
<div id="delImg" class="reveal-modal tiny" data-reveal></div>
