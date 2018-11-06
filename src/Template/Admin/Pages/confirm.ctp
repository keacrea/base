<div class="panel callout radius">
    <h5 class="">Suppression.</h5>
    <p>Confirmez-vous la suppression de cette page ?</p>
</div>
<?= $this->Html->link('Annuler','#', ['class'=>'button tiny radius alert left cancel']);?>
<?= $this->Form->postLink('Confirmer', ['action'=>'delete',$id], ['class'=>'button tiny radius success right']);?>

<?= $this->Html->script('admin/app.js', ['block'=>'scriptAjax', 'inline'=>false]);?>

