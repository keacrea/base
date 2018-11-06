<div class="panel callout radius">
    <h5 class="">Suppression.</h5>
    <p>Confirmez-vous la suppression du fichier ?</p>
</div>
<?= $this->Html->link('Annuler','#',['class'=>'button tiny radius alert left cancel']);?>
<?= $this->Html->link('Confirmer', ['action'=>'delete_img',$setting_site->id,$file],['class'=>'button tiny radius success right delimgMultiple']);?>

<?= $this->Html->script('admin/app.js', ['block'=>'scriptAjax']);?>