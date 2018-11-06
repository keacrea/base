<?php

namespace App\View\Helper;

use Cake\View\Helper;

class SwitchHelper extends Helper
{

    public $helpers = ['Form'];

    public function online($model, $id, $value)
    {

        $switch = $this->Form->create($model, ['url' => ['action' => 'online', 'prefix' => 'admin'], 'name' => 'switch']);
        $switch .= $this->Form->control('id', ['type' => 'hidden', 'value' => $id]);
        $switch .= $this->Form->control('online', [
            'type' => 'checkbox',
            'hiddenField' => false,
            'id' => 'online_' . $id,
            'checked' => $value,
            'class' => 'online',
            'label' => ''
        ]);
        $switch .= $this->Form->end();
        return '<div class="switch radius tiny">' . $switch . '</div>';
    }


    public function spotlight($model, $id, $value)
    {
        $switch = $this->Form->create($model, ['url' => ['action' => 'spotlight', 'prefix' => 'admin'], 'name' => 'switch']);
        $switch .= $this->Form->control('id', ['type' => 'hidden', 'value' => $id]);
        $switch .= $this->Form->control('spotlight', [
            'type' => 'checkbox',
            'hiddenField' => false,
            'id' => 'spotlight' . $id,
            'checked' => $value,
            'class' => 'online',
            'label' => ''
        ]);
        $switch .= $this->Form->end();
        return '<div class="switch radius tiny">' . $switch . '</div>';
    }

    public function home($model, $id, $value)
    {

        $switch = $this->Form->create($model, ['url' => ['action' => 'admin_home'],
            'inputDefaults' => ['div' => false, 'label' => false],
            'name' => 'switch'
        ]);
        $switch .= $this->Form->control('id', ['type' => 'hidden', 'value' => $id]);
        $switch .= $this->Form->control('home', [
            'type' => 'checkbox',
            'hiddenField' => false,
            'id' => 'home_' . $id,
            'checked' => $value,
            'class' => 'online',
            'label' => ''
        ]);
        $switch .= $this->Form->end();

        return '<div class="switch radius tiny">' . $switch . '</div>';

    }

}
