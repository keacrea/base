<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page $page
 */
$this->assign('title', ucfirst($page->meta_title));
echo $this->Html->meta('description', $page->meta_description, ['block' => true]);

$this->Breadcrumbs->add('Accueil', '/');
$this->element('parents_breadcrumbs', ['parents' => $parents]);

$myTemplates = [
    'inputContainer' => '{{content}}',
    'inputContainerError' => '<div class="input-error">{{content}}{{error}}</div>',
    'input' => '<input type="{{type}}" name="{{name}}"{{attrs}} />',
    'nestingLabel' => '{{input}}<label{{attrs}}>{{text}}</label>',
    'formGroup' => '{{label}}{{input}}',
];
$this->Form->setTemplates($myTemplates);
?>

<?= $this->element('header');?>
<?= $this->element('ariane');?>

<section class="content">
    <div class="grid-container">

        <div class="content__block">
        <div class="text-editor">
            <?= $page->content; ?>

            <form class="form">
                <?= $this->Form->create($contact, ['novalidate' => true]); ?>
                <?= $this->Flash->render(); ?>
                <div class="form__content">
                    <div class="grid-x align-center">
                        <div class="small-12 medium-10 large-8 cell">
                            <div class="grid-x grid-margin-x">
                                <div class="small-12 large-4 cell"></div>
                                <fieldset class="small-12 large-8 cell">
                                    <?= $this->Form->control('civility', ['error' => false, 'label' => false, 'type' => 'radio', 'options' => ['1' => 'Monsieur', '2' => 'Madame'], 'default' => '2']); ?>
                                </fieldset>
                                <div class="small-12 large-4  cell">
                                    <?= $this->Form->label('name', 'Nom'); ?>
                                </div>
                                <div class="small-12 large-8 cell">
                                    <?= $this->Form->control('name', ['label' => false, 'class' => 'form_input']); ?>
                                </div>

                                <div class="small-12 large-4  cell">
                                    <?= $this->Form->label('firstname', 'Prénom'); ?>
                                </div>
                                <div class="small-12 large-8 cell">
                                    <?= $this->Form->control('firstname', ['label' => false, 'class' => 'form_input']); ?>
                                </div>

                                <div class="small-12 large-4  cell">
                                    <?= $this->Form->label('phone', 'Téléphone'); ?>
                                </div>
                                <div class="small-12 large-8 cell">
                                    <?= $this->Form->control('phone', ['label' => false, 'class' => 'form_input']); ?>
                                </div>

                                <div class="small-12 large-4  cell">
                                    <?= $this->Form->label('email', 'Email'); ?>
                                </div>
                                <div class="small-12 large-8 cell">
                                    <?= $this->Form->control('email', ['label' => false, 'class' => 'form_input']); ?>
                                </div>
                                <div class="small-12 large-4  cell">
                                    <?= $this->Form->label('message', 'Message'); ?>
                                </div>
                                <div class="small-12 large-8 cell">
                                    <?= $this->Form->control('message', ['label' => false, 'class' => 'form_input', 'rows' => 4]); ?>
                                </div>
                                <div class="small-12 cell ">
                                    <div class="form__btn">
                                        <?= $this->Form->button('Envoyer la demande', ['class' => 'button']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?= $this->Form->end(); ?>
            </form>

        </div>
        </div>
    </div>




</section>

