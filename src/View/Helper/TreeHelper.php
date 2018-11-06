<?php

namespace App\View\Helper;

use Cake\Utility\Hash;
use Cake\View\Helper;


class TreeHelper extends Helper
{
    /**
     * @var array
     */
    public $helpers = ['Url', 'Html'];


    /**
     * @param $arr
     * @param string $class
     *
     * génère l'arboresence des pages avec les catégories
     * @internal param null $formations_nav
     */
    public function navigation_pages($arr, $class = 'navigation__list__link')
    {

        $controller = $this->request->getParam('controller');
        $action = $this->request->getParam('action');
        $pass = $this->request->getParam('pass');
        foreach ($arr as $v) {
            $active = '';
            if ($controller == $v->controller && $action == $v->action && (isset($pass[0]) && $pass[0] == $v->slug)) {
                $active = 'active';
            }

            if (!in_array($v->type, ['page'])) {
                if ($controller == $v->controller && $action == $v->action) {
                    $active = 'active';
                }

            }

            echo '<li class="' . $active . ' navigation__list__item">';

            if (!empty($v->slug)) {
                $contact = ($v->controller == 'Questionnaires') ? 'tip' : '';
                $url = (!empty($v->item)) ? '<a class="' . $class . ' no-link">' . ucfirst($v->item) . '</a>' : '';
                if (!$v->islink) {
                    $url = $this->Html->link(ucfirst($v->item), $v->url, ['escape' => false, 'class' => $active . ' ' . $class . ' ' . $contact]);
                }

                if (!empty($v->children)) {
                    echo $url;
                    echo '<ul class="menu nav__second">';
                    $this->navigation_subpages($v->children);
                    echo '</ul>';
                } else {
                    echo $url;
                }




            }


            echo '</li>';

        }
    }

    /**
     * @param $arr
     * @param string $class
     *
     * idem "navigation_pages" mais en responsive
     * @internal param $categories
     */
    public function nav_responsive($arr, $class = 'navigation-responsive__list__link')
    {
        $controller = $this->request->getParam('controller');
        $action = $this->request->getParam('action');
        $pass = $this->request->getParam('pass');
        foreach ($arr as $v) {
            $active = '';

            if ($controller == $v->controller && $action == $v->action && isset($pass[1]) && $pass[1] == $v->id) {
                $active = 'active';
            }

            echo '<li class="' . $active . ' navigation-responsive__list__item">';

            $url = (!empty($v->item)) ? '<a class="navigation-responsive__list__link no-link">' . ucfirst($v->item) . '</a>' : '';
            if (!$v->islink) {
                $url = $this->Html->link(ucfirst($v->item), $v->url, ['escape' => false, 'class' => $active . ' ' . $class]);
            }


            if (!empty($v->children)) {
                echo $url;
                echo '<ul class="menu vertical nested nav-resp__second">';
                $this->nav_responsive_subpage($v->children, $class);
                echo '</ul>';
            } else {
                echo $url;
            }



            echo '</li>';


        }
    }





    /**
     * @param $sub_categories
     *
     * boucle les sous pages
     */
    private function navigation_subpages($sub_categories)
    {
        $controller = $this->request->getParam('controller');
        $action = $this->request->getParam('action');
        $pass = $this->request->getParam('pass');
        foreach ($sub_categories as $v) {
            $active = '';

            if ($controller == $v->controller && $action == $v->action && isset($pass[0]) && $pass[0] == $v->slug) {
                $active = 'active';
            }

            $url = '<a class="no-link">' . ucfirst($v->item) . '</a>';
            if (!$v->islink) {
                $url = $this->Html->link(ucfirst($v->item), $v->url, ['escape' => false, 'class' => $active]);
            }

            echo '<li class="' . $active . '">';
            if (!empty($v->children)) {
                echo $url;
                echo '<ul class="menu nav__more">';
                $this->navigation_subpages($v->children);
                echo '</ul>';
            } else {
                echo $url;
            }
            echo '</li>';

        }
    }



    /**
     * @param $sub_categories
     * @param string $class
     *
     * idem "navigation_subpages" mais en responsive
     */
    private function nav_responsive_subpage($sub_categories,  $class = 'navigation-responsive__list__link')
    {
        $controller = $this->request->getParam('controller');
        $action = $this->request->getParam('action');
        $pass = $this->request->getParam('pass');
        foreach ($sub_categories as $v) {
            $active = '';

            if ($controller == $v->controller && $action == $v->action && $pass[0] == $v->slug) {
                $active = 'active';
            }

            echo '<li class="' . $active . ' navigation-responsive__list__item">';

            $url = '<a class="no-link">' . ucfirst($v->item) . '</a>';
            if (!$v->islink) {
                $url = $this->Html->link(ucfirst($v->item), $v->url, ['escape' => false, 'class' => $active . ' ' . $class]);
            }



            if (!empty($v->children)) {
                echo $url;
                echo '<ul class="menu nav-resp__second">';
                $this->nav_responsive_subpage($v->children, $class);
                echo '</ul>';
            } else {
                echo $url;
            }
            echo '</li>';

        }
    }

}
