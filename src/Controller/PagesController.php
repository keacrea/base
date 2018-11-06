<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{

    /**
     * Displays a view
     *
     * @return void
     */
    public function home()
    {

        $page = $this->Pages->find()
            ->where([
                'Pages.type' => 'home'
            ])
            ->first()
        ;

        if (!$page) {
            throw new NotFoundException('Cette page n\'existe pas!', 404);
        }

        $this->set(compact('page'));
    }



    public function view($slug = null)
    {

        if (!$slug) {
            throw new NotFoundException('Cette page n\'existe pas.');
        }

        $query = $this->Pages->find()
            ->where([
                'Pages.slug' => $slug
            ])
            ->contain([
                'ChildPages'
            ])
        ;

        if (!$this->request->getSession()->check('Auth.User.id')) {
            $query->where(function ($q) {
                $q->eq('Pages.online', true);
                return $q;
            });
        }

        $page= $query->first();

        if (!$page) {
            throw new NotFoundException('Cette page n\'existe pas!', 404);
        }

        $parents = $this->Pages->find('path', ['for' => $page->id])
            ->select([
                'id',
                'parent_id',
                'slug',
                'controller',
                'item',
                'action',
                'type',
                'islink'
            ])
            ->toArray();
        $this->set(compact('page', 'parents'));

    }
}
