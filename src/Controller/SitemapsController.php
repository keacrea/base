<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class SitemapsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->viewBuilder()->setLayout('ajax');

    }

    function index()
    {
        if (!$this->request->is('xml')) {
            throw new NotFoundException('Cette page n\'existe pas');
        }
        $pages = TableRegistry::getTableLocator()->get('Pages')->find()
            ->where([
                'online' => true,
                'type in' => [
                    'page',
                    ],
            ])
            ->select(['id', 'slug', 'type'])
            ->all();

        $this->set(compact('pages'));
        $this->set(['_serialize' => false]);
    }
}
