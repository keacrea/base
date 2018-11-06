<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page $page
 */
?>
<header class="header">
    <div class="grid-container">
        <div class="header__content">
            <h1><?= ucfirst($page->name); ?></h1>
            <div class="header__chapo">
                <?= ucfirst($page->chapo); ?>
            </div>
        </div>
    </div>
</header>
