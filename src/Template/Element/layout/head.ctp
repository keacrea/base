<head>
	<?= $this->Html->charset() ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		<?= $this->fetch('title') ?>
	</title>
    <?= $this->element('favicon') ?>
	<?= $this->Html->meta('icon') ?>
	<?= $this->Html->css('app.css') ?>
	<?= $this->fetch('meta') ?>
	<?= $this->fetch('css') ?>
</head>
