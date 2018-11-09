<?php $this->assign('title','Gestion des administrateurs'); ?>
<?php $this->Breadcrumbs->add('Gestion des administrateurs', ['controller' => 'Users', 'action' => 'index']);?>

<div class="row">
    <div class="small-12 columns">
        <div class="portlet">
            <div class="row portlet__header">
                <div class="small-9 columns">
                    <h2 class="portlet__title"> Gestion des administrateurs</h2>
                </div>
                <div class="small-3 columns text-right">
                    <?= $this->Html->link('Ajouter un administrateur', ['controller'=>'Users','action'=>'add','prefix'=>'admin'],['class'=>'button success tiny radius no-margin ']);?>

                </div>
            </div>

            <div class="row">
                <div class="small-12 columns">
                    <table class="has-even-stripes">
                        <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('name','Nom');?></th>
                            <th><?= $this->Paginator->sort('email','Email');?></th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?= $this->Form->create(null,['type'=>'get','novalidate'=>true,'inputDefaults'=>['div'=>false,'label'=>false]]);?>
                        <tr class="search">
                            <td><?= $this->Form->control('name',['placeholder'=>'Rechercher un administrateur', 'class' => 'no-margin','value'=>$this->request->getQuery('name'),'label'=>false]);?></td>
                            <td><?= $this->Form->control('mail',['placeholder'=>'Rechercher un email', 'class' => 'no-margin','value'=>$this->request->getQuery('mail'),'label'=>false]);?></td>

                            <?php if($this->request->getQuery()):?>
                                <td class="text-center">
                                    <?= $this->Html->tag('button','Rechercher',['type' => 'submit', 'class'=>'button no-margin tiny radius']);?>
                                    <?= $this->Html->link('Réinitialiser',['controller' => 'Users','action'=>'index','prefix'=>'admin'],['class'=>'button secondary tiny radius no-margin']);?></td>
                            <?php else: ?>
                                <td class="text-center"><?= $this->Html->tag('button','Rechercher',['type' => 'submit', 'class'=>'button no-margin tiny radius']);?></td>
                            <?php endif;?>
                        </tr>
                        <?= $this->Form->end();?>
                        <?php foreach($users as $user): ?>
                            <tr>
                                <td><?= $user->name;?></td>
                                <td><?= $user->email;?></td>
                                <td class="text-center">
                                    <?php if(($this->request->getSession()->read('Auth.User.id') == $user->id) || $this->request->getSession()->read('Auth.User.role') == 'superadmin'):?>
                                    <?= $this->Html->link(null,
                                        ['action'=>'edit',$user->id,'prefix'=>'admin'],
                                        ['class'=>'fa fa-pencil-square-o action action-edit','title'=>'Modifier l\'administrateur']); ?>
                                    <?php endif;?>
                                    <?php if(($this->request->getSession()->read('Auth.User.id') != $user->id) && $this->request->getSession()->read('Auth.User.role') == 'superadmin'):?>
                                    <?= $this->Html->link(null,
                                            ['action' => 'confirm',$user->id,'prefix'=>'admin'],
                                            ['data-reveal-id'   => 'delModal','data-reveal-ajax' => 'true','class' => 'del fa fa-trash-o action action-delete', 'title'=> 'Supprimer l\'administrateur']); ?>
                                    <?php endif;?>
                                </td>
                            </tr>
                        <?php endforeach;?>

                        </tbody>
                    </table>

                </div>
            </div>
            <div class="row">

                <div class="small-12 columns">
                    <ul class="pagination right">
                        <?= $this->Paginator->prev('<i class="fa fa-chevron-left"></i>', ['tag'=>'li','escape'=>false],null, ['tag'=>'span','class' => 'prev hide']); ?>
                        <?= $this->Paginator->numbers(['tag'=>'li','separator'=>false]);?>
                        <?= $this->Paginator->next('<i class="fa fa-chevron-right"></i>', ['tag'=>'li','escape'=>false],null, ['tag'=>'span','class' => 'next hide']); ?>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
<div id="delModal" class="reveal-modal tiny" data-reveal></div>
