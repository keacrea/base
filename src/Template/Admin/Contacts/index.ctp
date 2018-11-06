<?php
$this->assign('title',$title);
$this->Breadcrumbs->add(
    $title,
    ['controller' => 'Contacts', 'action' => 'index','prefix'=>'admin']
);

$myTemplates = [
    'inputContainer' => '{{content}}',
    'nestingLabel' => '{{input}}<label{{attrs}}>{{text}}</label>',
    'formGroup' => '{{input}}{{label}}',
];
$this->Form->setTemplates($myTemplates);
?>

<div class="row">
    <div class="small-12 columns">
        <div class="portlet">
            <div class="row portlet__header">
                <div class="small-4 columns">
                    <h2 class="portlet__title"><?= $title;?></h2>
                </div>
                <div class="small-8 columns text-right">
                    <?= $this->Form->create(null, [
                        'url' => [
                            'action' => 'export',
                        ],
                        'class' => 'form'
                    ] );
                    ?>
                    <div class="row text-left">
                        <div class="small-4 columns">
                            <div class="row">
                                <div class="small-4 columns">
                                    <label for="" class="inline">Début</label>
                                </div>
                                <div class="small-8 columns">
                                    <?= $this->Form->control('start-export', ['placeholder' => 'Date de début', 'div' => 'input input-portlet-title', 'label' => false, 'id' => 'start', 'autocomplete' => 'off']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="small-4 columns">
                            <div class="row">
                                <div class="small-4 columns">
                                    <label for="" class="inline">Fin</label>
                                </div>
                                <div class="small-8 columns">
                                    <?= $this->Form->control('end-export', ['placeholder' => 'Date de fin', 'div' => 'input input-portlet-title','label' => false,'id' => 'end', 'autocomplete' => 'off']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="small-4 columns">
                            <?= $this->Form->submit('Exporter', ['placeholder' => 'Date de fin','div' => 'input input-portlet-title','class' => 'btn button small radius']);?>
                        </div>
                    </div>
                    <?= $this->Form->end();?>
                </div>

            </div>

            <div class="row">
                <div class="small-12 columns">
                    <table class="has-even-stripes">
                        <thead>
                        <tr>
                            <th width="3%"><?= $this->Form->checkbox('checkAll', ['class' => 'check-all','id'=>'checkAll']); ?></th>
                            <th width="20%">Date de réception</th>
							<th width="15%">Nom</th>
                            <th width="35%">Email</th>
                            <th width="15%" class="text-center" colspan="2">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?= $this->Form->create(null,['type'=>'get','novalidate'=>true]);?>
                        <tr class="search">
                            <td></td>
                            <td></td>
                            <td><?= $this->Form->control('name', ['placeholder'=>'Rechercher un contact par le nom', 'class' => 'no-margin','value'=>$this->request->getQuery('name'),'label'=>false]);?></td>
                            <td><?= $this->Form->control('email', ['placeholder'=>'Rechercher un contact par l\'email', 'class' => 'no-margin','value'=>$this->request->getQuery('mail'),'label'=>false]);?></td>
                            <?php if($this->request->getQuery()):?>
                                <td class="text-center"><?= $this->Html->tag('button','Rechercher',['type' => 'submit', 'class'=>'button no-margin tiny radius']);?></td>
                                <td class="text-center"><?= $this->Html->link('Réinitialiser',['controller' => 'Contacts','action'=>'index','prefix'=>'admin'],['class'=>'button secondary tiny radius no-margin']);?></td>
                            <?php else: ?>
                                <td class="text-center" colspan="2"><?= $this->Html->tag('button','Rechercher',['type' => 'submit', 'class'=>'button no-margin tiny radius']);?></td>
                            <?php endif;?>
                        </tr>
                        <?= $this->Form->end();?>
                        <?php foreach($contacts as $contact): ?>
                            <tr>
                                <td><?=$this->Form->control('Contacts.check'.$contact->id,['value'=>$contact->id,'type'=>'checkbox','label'=>false,'div'=>false,'class'=>'checkbox']);?></td>
                                <td><?= $contact->created->format('d/m/Y H:i');?></td>
                                <td><?= ucfirst($contact->name);?></td>
                                <td><?= ucfirst($contact->email);?></td>

                                <td class="text-center" colspan="2">
                                    <?php $unread = ($contact->readable) ? '' : '-open-o';?>
                                    <?= $this->Html->link(null, ['action'=>'view',$contact->id,'prefix'=>'admin'], ['class'=>'fa fa-folder'.$unread.' action action-edit','title'=>'Lire la demande']);?>
                                    <?= $this->Html->link(null, ['action' => 'confirm', $contact->id, 'prefix'=>'admin'], ['data-reveal-id'   => 'delModal', 'data-reveal-ajax' => 'true', 'class' => 'del fa fa-trash-o action action-delete', 'title'  => 'Supprimer le contact']); ?>
                                </td>
                            </tr>
                        <?php endforeach;?>

                        </tbody>
                    </table>

                </div>
            </div>
            <div class="row">
                <?= $this->Form->create(null,['url'=>['controller'=>'Contacts','action'=>'actions'],'id'=>'form-groupee']);?>
                <div class="small-6 columns">
                    <div class="row">
                        <div class="small-6 columns">
                            <?php $options = ['delete'=>'Supprimer'];?>
                            <?= $this->Form->control('action', ['options'=>$options, 'empty'=>'--','label'=>false,'id'=>'type-action']);?>
                        </div>
                        <div class="small-6 columns">
                            <?= $this->html->tag('button', 'Ok', ['type' => 'submit', 'class'=>'button success tiny radius','id'=>'action-groupee']);?>
                        </div>
                    </div>

                </div>
                <?= $this->Form->end();?>
                <div class="small-6 columns">
                    <ul class="pagination right">
                        <?= $this->Paginator->prev('<i class="fa fa-chevron-left"></i>', ['contact'=>'li','escape'=>false],null, ['contact'=>'span','class' => 'prev hide']); ?>
                        <?= $this->Paginator->numbers(['contact'=>'li','separator'=>false]);?>
                        <?= $this->Paginator->next('<i class="fa fa-chevron-right"></i>', ['contact'=>'li','escape'=>false],null, ['contact'=>'span','class' => 'next hide']); ?>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

<div id="delModal" class="reveal-modal tiny" data-reveal></div>
<div id="delAllModal" class="reveal-modal tiny" data-reveal>
    <div class="panel callout radius">
        <h5>Confirmation.</h5>
        <p>Confirmez-vous la suppression des contacts sélectionnés ?</p>
    </div>
    <?= $this->Html->link('Annuler','#', ['class'=>'button tiny radius alert left cancel']);?>
    <?= $this->Html->link('Confirmer','#', ['class'=>'button tiny radius success right','id'=>'validDel']);?>
</div>

