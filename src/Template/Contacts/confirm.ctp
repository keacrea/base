<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page $page
 */

$this->assign('title', ucfirst($page->meta_title));
echo $this->Html->meta('description', ucfirst($page->meta_description), ['block' => true]);
echo $this->Html->meta(['name' => 'robots',  'content' => 'noindex'],null, ['block'=>true]);

$this->Breadcrumbs->add('Accueil', '/');
$this->Breadcrumbs->add('confirmation', '' , ['innerAttrs' => ['class' => 'current']]);
?>


<?= $this->element('header');?>
<?= $this->element('ariane');?>


<section class="content">
    <div class="grid-container">
        <div class="content__block">
            <div class="text-editor">
                <p><?= ($contact->civility == 1) ? 'Cher M.' : 'Chère Mme' ;?>  <?= mb_strtoupper($contact->name);?>,</p>
                <?= $page->content; ?>
            </div>
        </div>
    </div>
</section>
