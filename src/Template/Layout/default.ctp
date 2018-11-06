<!DOCTYPE html>
<html lang="fr">
<?= $this->element('layout/head'); ?>
<body>

<div class="top">
    <div class="grid-container">
        <div class="top__content">
            <div class="top__logo">
                <?= $this->Html->link($this->Html->image($settings_site->logo, ['alt' => 'Logo']), '/', ['escape' => false] );?>
            </div>
            <div class="top__nav">
                <?= $this->cell('Navigation::navigation'); ?>
            </div>
        </div>
    </div>
</div>

<?= $this->Flash->render() ?>
<?= $this->fetch('content') ?>

<footer class="footer">
    <div class="grid-container">
            <?= $this->cell('Navigation::navigation_footer'); ?>

    </div>
</footer>

<?= $this->element('layout/script'); ?>
</body>
</html>
