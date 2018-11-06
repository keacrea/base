<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page[]| Cake\Collection\CollectionInterface $parents
 */

foreach ($parents as $k => $parent):
    $last_items = end($parents);
    if ($last_items->slug == $parent->slug) {
        $this->Breadcrumbs->add(ucfirst($parent->item), '' , ['innerAttrs' => ['class' => 'current']]);
    } else {
        if ($parent->islink){
            $this->Breadcrumbs->add(ucfirst($parent->item), '' , ['innerAttrs' => ['class' => 'disabled']]);
        } else{
            $this->Breadcrumbs->add(ucfirst($parent->item), $parent->url);
        }
    }
endforeach;
