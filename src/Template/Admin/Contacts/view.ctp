<?php
$this->assign('title',$title);
$this->Breadcrumbs->add([
    ['title' => 'Demandes de contact reçues', 'url' => ['controller' => 'Contacts', 'action' => 'index','prefix'=>'admin']],
    ['title' => $title]
]);

?>

<div class="row">
    <div class="small-12 columns">
        <div class="portlet">
            <div class="row portlet__header">
                <div class="small-9 columns">
                    <h1 class="portlet__title"> <?= $title;?></h1>
                </div>
                <div class="small-3 columns">
                    <?= $this->Html->link('Retour aux messages',$redirect = ($this->request->getSession()->check('Temp.referer')) ? $this->request->getSession()->read('Temp.referer') : ['action'=>'index'], ['class'=>'button secondary tiny radius right no-margin']);?>
                </div>
            </div>

            <div class="row">
                <div class="small-12 columns">
                    <table>

                        <tr>
                            <th>Civilité</th>
                            <td><?= ($contact->civility == 1) ? 'Monsieur' : 'Madame';?></td>
                        </tr>
                        <tr>
                            <th>Nom</th>
                            <td><?= h(strtoupper($contact->name));?></td>
                        </tr>
                        <tr>
                            <th>Prénom</th>
                            <td><?= h($contact->firstname);?></td>
                        </tr>
                        <tr>
                            <th>Téléphone</th>
                            <td><?= h($contact->phone);?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?= h($contact->email);?></td>
                        </tr>
                        <tr>
                            <th>Date de réception</th>
                            <td>Le <?= h($contact->created->format('d/m/Y à H:i'));?></td>
                        </tr>
                        <tr>
                            <th>Message</th>
                            <td><?= nl2br(h($contact->message));?></td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
