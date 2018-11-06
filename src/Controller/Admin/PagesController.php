<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\I18n\I18n;
use Cake\Network\Exception\NotFoundException;
use Cake\Utility\Hash;

/**
 * Pages Controller
 *
 * @property \App\Model\Table\PagesTable $Pages
 * @property \App\Controller\Component\SaveCloseComponent $SaveClose
 */
class PagesController extends AppController
{
    public function initialize(){
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->loadComponent('SaveClose');
    }

    public function beforeFilter(Event $event){
        parent::beforeFilter($event);
		$this->Security->setConfig('unlockedActions', ['actions','online', 'delete_img']);
    }

    public function isAuthorized($user)
    {
        if(in_array($user['role'],['admin','superadmin'])){
            return true;
        }
        return false;
    }

    /**
     */
    public function index()
    {

	    if($this->getRequest()->getSession()->check('Temp')){
            $this->getRequest()->getSession()->delete('Temp');
        }

        $this->set('title','Gestion des contenus');
        $this->set('types',$this->Pages->type);
        $this->paginate = [
            'limit' =>25,
            'fields'=>['id','item','type','online','position','parent_id','menu_id', 'type'],
            'finder' => [
                'parent' => ['parent_id' => $this->getRequest()->getQuery('parent_id')]
            ],
            'contain'=>['ChildPages'=>[
                'fields'=>['id','parent_id']
            ]],
            'order'=>['Pages.position'=>'ASC']
        ];
        foreach($this->getRequest()->getQuery() as $k =>  $v){
            if(!empty($v) &&  $k != 'page' && $k != 'sort' && $k != 'direction'){
                if(in_array($k,['menu_id','parent_id'])){
                    $this->paginate['conditions']['Pages.'.$k] =  trim($v);
                }else{
                    $this->paginate['conditions']['Pages.'.$k.' LIKE'] =  '%'.trim($v).'%';
                }
            }
        }
        if(is_null($this->getRequest()->getQuery('menu_id'))){
            $this->redirect(['action'=>'index','?'=>['menu_id'=>1]]);
        }

        try{
            $pages = $this->paginate($this->Pages);
        } catch(NotFoundException $e){
            $this->redirect(['action'=>'index']);
        }

        if($this->getRequest()->getQuery('parent_id')){
            $parent = $this->Pages->find('path',['for'=>$this->getRequest()->getQuery('parent_id'),'fields'=>['id','parent_id','item']])
                ->toArray();
            $parent = end($parent);
            $this->set('parent', $parent);
        }

        $this->set(compact('pages'));

    }

	/**
	 * @param null $type
	 *
	 * @return mixed
	 * @internal param string $lang
	 *
	 */
    public function add($type = null)
    {
        $page = $this->Pages->newEntity();
        if(!$type){
            $type = 'page';
        }

        $this->set('type',$type);

        if ($this->getRequest()->is('post')) {

            $page = $this->Pages->patchEntity($page, $this->getRequest()->getData());

            if ($this->Pages->save($page)) {
                $this->Flash->success('Le contenu a bien été enregistré.');

                $this->SaveClose->redirect($page->id);

            } else {
                $this->Flash->error('Merci de vérifier les informations saisies');
            }
        }

        $parents = $this->Pages->listPageMenu(1);

        $menus = $this->Pages->Menus->find('list');

        if(!$this->getRequest()->getSession()->check('Temp')){
            if($type == 'page'){
                $this->getRequest()->getSession()->write('Temp', $this->referer());
            }
        }
        $this->set(compact('page','parents', 'menus'));
    }

	/**
	 * Edit method
	 *
	 * @param null $id
	 * @param null $type
	 * @param string $lang
	 *
	 * @return \Cake\Network\Response|void
	 */
    public function edit($id = null,$type = null)
    {
        $page = $this->Pages->find()
        ->where(['id' => $id])
        ->first();
            if(!$type){
                $type = 'page';
            }

        $this->set('type',$type);

        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $page = $this->Pages->patchEntity($page, $this->getRequest()->getData());

            if ($this->Pages->save($page)) {

                $this->Flash->success('Le contenu a bien été mis à jour.');

                $this->SaveClose->redirect($page->id);

            } else {
                $this->Flash->error('Merci de verifier les informations saisies.');
            }

        }
        if(!$this->getRequest()->getSession()->check('Temp')){
            $this->getRequest()->getSession()->write('Temp',$this->referer());
        }
        $parents = $this->Pages->listPageMenu($page->menu_id, $page->id);
        $menus = $this->Pages->Menus->find('list');
        $this->set(compact('page','parents', 'menus'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Page id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $page = $this->Pages->get($id);
        if ($this->Pages->delete($page)) {
            $this->Flash->success('La page a bien été surpprimée');
        } else {
            $this->Flash->error('Impossible de supprimer la page.');
        }
        return $this->redirect($this->referer());
    }

    /**
     * @param $id
     */
    public function confirm($id){
        $this->viewBuilder()->setLayout('ajax');
        $this->set('id',$id);

    }

    public function online(){
        if($this->getRequest()->is('ajax')){
            $page = $this->Pages->find()->where(['Pages.id' => $this->getRequest()->getData('id')])->first();
            $page->online = (!empty($this->getRequest()->getData('online'))) ? true : false;
            $this->Pages->save($page);
        }
        exit();
    }

    public function actions(){

        if($this->getRequest()->is('post')){
            Cache::deleteMany(['pages'],'nav');
            $data = $this->getRequest()->getData();
            $statut = 0;
            if(empty($data['action']) || empty($data['datas'])){
                $statut = 0;
            }
            $pageIds = hash::extract($data['datas'],'{n}.value');

            if($data['action'] == 'offline'){
            	if($this->Pages->query()
		            ->update()
		            ->set(['online'=>false])
		            ->where(['Pages.id IN'=>$pageIds])
		            ->execute()){
		            $statut = 1;
	            }
            }

            if($data['action'] == 'online'){

	            if($this->Pages->query()
	                           ->update()
	                           ->set(['online'=>true])
	                           ->where(['Pages.id IN'=>$pageIds])
	                           ->execute()){
		            $statut = 1;
	            }

            }

            if($data['action'] == 'delete'){
                foreach($pageIds as $pId){
                    $pageId = $this->Pages->find()
                        ->select(['id','type'])
                        ->where(['Pages.id'=>$pId])
                        ->first();
                    if($pageId){
                        $this->Pages->delete($pageId);
                    }
                }
                $statut = 1;
            }
            echo json_encode($statut);
        }

        exit();
    }

    /**
     * @param null $id
     * @param null $menu_id
     */
    public function movedown($id = null, $menu_id = null) {
        Cache::deleteMany(['pages'],'nav');
        $this->Pages->to_down($id, $menu_id);
        $this->redirect($this->referer());
    }

    /**
     * @param null $id
     * @param null $menu_id
     */
    public function moveup($id = null, $menu_id = null) {
        Cache::deleteMany(['pages'],'nav');
        $this->Pages->to_up($id, $menu_id);
        $this->redirect($this->referer());
    }

    public function order() {
        Cache::deleteMany(['pages'],'nav');
        $data = $this->getRequest()->getData();
        if(!$this->Pages->position($data)){
            $this->Flash->error('La position demandée est supérieure au nombre de lignes');
        }

        $this->redirect($this->referer());
    }


	public function confirm_img($id, $file = 'image'){
		$this->viewBuilder()->setLayout('ajax');
		if($id)
			$page = $this->Pages->find()
			                          ->where(['Pages.id'=>$id])
			                          ->select(['id',$file])
			                          ->first();

		$this->set(compact('page','file'));

	}

	public function delete_img($id, $file = 'image'){
		$this->viewBuilder()->setLayout('ajax');

		$img = $this->Pages->find()
		                      ->where(['Pages.id'=>$id])
		                      ->select([$file])
		                      ->first();

		if($img){
			$this->Pages->delete_recursive_min($img->$file);
			$page = $this->Pages->get($id);
            $page->$file = '';
			if($this->Pages->save($page )){
				$return = ['statut'=>1,'value'=>$id,'type'=>$file];
				echo json_encode($return);
			}else{
				$return = ['statut'=>0,'value'=>$id,'type'=>$file];
				echo json_encode($return);
			}

		}

		exit();

	}
}
