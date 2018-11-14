<?php

namespace App\View\Helper;

use Cake\Core\Plugin;
use Cake\View\Helper;

class PluginHelper extends Helper
{

    /**
     * @return string
     * Retourne le menu en fonction des plugins chargés
     * TODO récupérer la liste de tous les plugins plutôt que le tableau
     */
    public function loadMenus()
    {
        $result = '';

        $plugins = [
            'Blog',
            'Faq',
            'Team',
            'Arguments'
        ];

        foreach ($plugins as $plugin){
            $path = 'menu_admin/' . mb_strtolower($plugin);
            if (Plugin::loaded($plugin)){
                if ($this->_View->elementExists($path)){
                    $result .= $this->_View->element($path);
                }
            }
        }

        return $result;
    }

}
