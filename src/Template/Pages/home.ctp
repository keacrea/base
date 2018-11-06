<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page $page
 */

$this->assign('title', ucfirst($page->meta_title));
echo $this->Html->meta('description', $page->meta_description, ['block' => true]);

?>

<?= $this->element('header');?>

<section class="content">
    <div class="grid-container">
        <div class="content__block">
            <div class="text-editor">
                <?= $page->content; ?>
            </div>
        </div>
    </div>
</section>

