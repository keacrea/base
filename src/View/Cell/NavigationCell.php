<?php

namespace App\View\Cell;

use Cake\Cache\Cache;
use Cake\Collection\Collection;
use Cake\Database\Query;
use Cake\I18n\I18n;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\View\Cell;

class NavigationCell extends Cell
{

    private function _getNavPage()
    {

        if (($pages = Cache::read('pages', 'nav')) === false) {
            $page = TableRegistry::getTableLocator()->get('Pages');

            $pages = $page->find('threaded')
                ->select(['id', 'slug', 'item', 'position', 'controller', 'action', 'type', 'parent_id', 'islink'])
                ->where([
                    'Pages.online' => true,
                    'Pages.menu_id' => 1,
                    'Pages.visible' => true,
                ])
                ->order([
                    'position' => 'ASC'
                ])
                ->toArray();
            Cache::write('pages', $pages, 'nav');
        }

        return $pages;
    }
    private function _getNavResp()
    {
        if (($pages = Cache::read('pages-resp', 'nav')) === false) {
            $page = TableRegistry::getTableLocator()->get('Pages');

            $pages = $page->find('threaded')
                ->select(['id', 'slug', 'item', 'position', 'controller', 'action', 'type', 'parent_id', 'islink'])
                ->where([
                    'Pages.online' => true,
                    'Pages.menu_id' => 1,
                    'Pages.visible' => true,
                ])
                ->order([
                    'position' => 'ASC',
                ])
                ->toArray();
            Cache::write('pages-resp', $pages, 'nav');
        }

        return $pages;
    }


    public function navigation_responsive($mobile = true)
    {
        $this->set('mobile',$mobile);
        $this->set('navigationResp', $this->_getNavResp());

    }

    public function navigation($parents = null,$mobile= false)
    {
        $this->set('parents', $parents);
        $this->set('mobile', $mobile);
        $this->set('navigation', $this->_getNavPage());

    }


    public function navigation_footer()
    {
        if (($pages_footer = Cache::read('navigation_footer', 'nav')) === false) {
            $page = TableRegistry::getTableLocator()->get('Pages');
            $pages_footer = $page->find('all')
                ->select(['id', 'slug', 'item', 'position', 'controller', 'action', 'type','islink'])
                ->where([
                    'Pages.online' => true,
                    'Pages.menu_id' => 2,
                    'Pages.visible' => true,
                ])
                ->order(['position' => 'ASC'])
                ->toArray();
            Cache::write('navigation_footer', $pages_footer, 'nav');
        }

        $this->set('navigation_footer', $pages_footer);
    }

}
