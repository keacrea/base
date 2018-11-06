<?php
$this->assign('title','Gestion des contenus');
$this->Breadcrumbs->add('Gestion des contenus', ['controller' => 'Pages', 'action' => 'index','prefix'=>'admin']);

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
                <div class="small-9 columns">
                    <h2 class="portlet__title">Gestion des contenus</h2>
                </div>
                <div class="small-3 columns text-right">
                    <?php if(isset($parent)):
                        echo $this->Html->link('Retour', [
                            'action'=>'index',
                            'prefix'      => 'admin',
                            '?'=>['menu_id'=>$this->request->getQuery('menu_id'),
                                'parent_id'=>$parent->parent_id]
                            ],
                            ['class'=>'button tiny secondary radius no-margin','title'=>'Retour au parent']);
                    endif ?>

                    <?= $this->Html->link('Ajouter une page', ['controller'=>'Pages','action'=>'add','prefix'=>'admin'],['class'=>'button success tiny radius no-margin']);?>

                </div>
            </div>

            <div class="row">
                <div class="small-12 columns">
                    <table class="has-even-stripes">
                        <thead>
                        <tr>
                            <th width="3%"><?= $this->Form->checkbox('checkAll', ['class' => 'check-all','id'=>'checkAll']); ?></th>
                            <th width="30%">Titre de la page</th>
                            <th width="15%">Type de page</th>
                            <th width="15%" class="text-center">Publier</th>
                            <th width="10%" class="text-center">Position</th>
                            <th width="15%" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?= $this->Form->create(null,['type'=>'get','novalidate'=>true]);?>
                        <tr class="search">

                            <td>
                                <?= $this->Form->control('menu_id',['value'=>$this->request->getQuery('menu_id'),'type'=>'hidden']);?>
                            </td>
                            <td><?= $this->Form->control('item', ['placeholder'=>'Rechercher une page', 'class' => 'no-margin','value'=>$this->request->getQuery('item'),'label'=>false]);?></td>
                            <td><?= $this->Form->control('type', ['empty'=>'-----',"options"=>$types, 'class' => 'no-margin','value'=>$this->request->getQuery('type'),'label'=>false]);?></td>
                            <?php if($this->request->getQuery()):?>
                                <td class="text-center"><?= $this->Html->tag('button','Rechercher',['type' => 'submit', 'class'=>'button no-margin tiny radius']);?></td>
                                <td class="text-center"><?= $this->Html->link('Réinitialiser',['controller' => 'Pages','action'=>'index','prefix'=>'admin','?'=>['menu_id'=>$this->request->getQuery('menu_id')]],['class'=>'button secondary tiny radius no-margin']);?></td>
                                <td></td>
                            <?php else: ?>
                                <td class="text-center"><?= $this->Html->tag('button','Rechercher',['type' => 'submit', 'class'=>'button no-margin tiny radius']);?></td>
                                <td colspan="2"></td>
                            <?php endif;?>
                        </tr>
                        <?= $this->Form->end();?>
                        <?php foreach($pages as $page): ?>
                            <tr>
                                <td><?php
                                    $excludeCheck = ['static','form','confirm','home','category', 'presentation'];
                                    if(!in_array($page->type,$excludeCheck)) {
                                        echo $this->Form->control('Pages.check'.$page->id,['value'=>$page->id,'type'=>'checkbox','label'=>false,'div'=>false,'class'=>'checkbox']);
                                    } ?></td>
                                <td><?= ucfirst($page->item);?></td>
                                <td><?= $this->Type->showType($types,$page->type);?></td>

                                <td class="text-center">
                                    <?php
                                    $exclude = ['confirm','home', 'category', 'form', 'presentation'];
                                    if(!in_array($page->type,$exclude)) {
                                        echo $this->Switch->online('Page',$page->id,$page->online);
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?= $this->Html->link(null, ['action'=>'movedown',$page->id,$page->menu_id,'prefix'=>'admin'],
                                        ['class' => 'fa fa-caret-down arrow','escape'=>false,'title'=>'Descendre']);?>
                                    <!-- Bloc pour reorder -->
                                    <a href="#" data-options="align:top" data-dropdown="drop_<?= $page->id; ?>" class="link-position" title="Changer de position"><?= $page->position; ?></a>
                                    <div id="drop_<?= $page->id; ?>" class="tiny content f-dropdown dropdown-position" data-dropdown-content>

                                        <?= $this->Form->create(null, [
                                            'url'=> [
                                                'action'=>'order',
                                                $page->parent_id,
                                                'prefix'=>'admin'
                                            ],
                                            'id'=>'order',
                                            'name'=>'order']);
                                        ?>
                                        <div class="row collapse">
                                            <label class="title-form-position">Entrez une position.</label>
                                            <?= $this->Form->control('id', ['value'=>$page->id,'type'=>'hidden']);?>

                                            <?= $this->Form->control('menu_id', ['value'=>$page->menu_id,'type'=>'hidden']);?>
                                            <div class="small-8 columns">
                                                <?= $this->Form->control('position', ['value'=>$page->position,'label'=>false]);?>
                                            </div>
                                            <div class="small-3 columns">
                                                <?= $this->Form->button('Ok', ['class'=>'button success radius tiny']);?>
                                            </div>

                                        </div>
                                        <?= $this->Form->end();?>
                                    </div>
                                    <!-- end bloc pour reorder -->

                                    <?= $this->Html->link(null, ['action'=>'moveup',$page->id,$page->menu_id,'prefix'=>'admin'],
                                        ['class' => 'fa fa-caret-up arrow','escape'=>false,'title'=>"Monter"]);?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    if($page->child_pages){
                                        echo $this->Html->link(
                                            null,
                                            [
                                                'action' => 'index',
                                                'prefix'=>'admin',
                                                '?'=>['menu_id'=>$this->request->getQuery('menu_id'),'parent_id'=>$page->id]
                                            ],
                                            [
                                                'class' => 'fa fa-folder-open action action-view',
                                                'title' => 'Voir les sous-pages'
                                            ]
                                        );
                                    }
                                    ?>
                                    <?php
                                    $type = ($page->type != 'page') ? $page->type : '';
                                        echo $this->Html->link(null, ['action' => 'edit', $page->id, $type, 'prefix' => 'admin'], ['class' => 'fa fa-pencil-square-o action action-edit', 'title' => 'Modifier la page']);

                                    ?>

                                    <?php
//                                        echo $page->menu_id;
                                    $exclude = ['static','form','confirm','home', 'presentation'];
                                    if(!in_array($page->type,$exclude)) {
                                        if(empty($page->child_pages)){
                                            echo $this->Html->link(
                                                null,
                                                [
                                                    'action' => 'confirm',
                                                    $page->id,
                                                    'prefix'=>'admin'
                                                ],
                                                [
                                                    'data-reveal-id'   => 'delModal',
                                                    'data-reveal-ajax' => 'true',
                                                    'class'            => 'del fa fa-trash-o action action-delete',
                                                    'title'            => 'Supprimer la page'
                                                ]
                                            );
                                        }

                                    }


                                    ?>

                                </td>
                            </tr>
                        <?php endforeach;?>

                        </tbody>
                    </table>

                </div>
            </div>
            <div class="row">
                <?= $this->Form->create(null,['url'=>['controller'=>'Pages','action'=>'actions'],'id'=>'form-groupee']);?>
                <div class="small-6 columns">
                    <div class="row">
                        <div class="small-6 columns">
                            <?php $options = ['delete'=>'Supprimer','online'=>'Publier','offline'=>'Dépublier'];?>
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
<div id="delAllModal" class="reveal-modal tiny" data-reveal>
    <div class="panel callout radius">
        <h5>Confirmation.</h5>
        <p>Confirmez-vous la suppression des pages sélectionnées et des sous-pages associées ?</p>
    </div>
    <?= $this->Html->link('Annuler','#', ['class'=>'button tiny radius alert left cancel']);?>
    <?= $this->Html->link('Confirmer','#', ['class'=>'button tiny radius success right','id'=>'validDel']);?>
</div>

