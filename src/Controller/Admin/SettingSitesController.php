<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * SettingSites Controller
 *
 * @property \App\Model\Table\SettingSitesTable $SettingSites
 */
class SettingSitesController extends AppController
{

	public function initialize(){
		parent::initialize();
		$this->loadComponent('Paginator');

	}

	public function beforeFilter(Event $event){
		parent::beforeFilter($event);
	}

    public function isAuthorized($user)
    {
        if(in_array($user['role'],['admin','superadmin'])){
            return true;
        }
        return false;

    }
    /**
     * Edit method
     *
     *
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit()
    {
        $settingSite = $this->SettingSites->get(1);


        if ($this->getRequest()->is(['patch', 'post', 'put'])) {

            $settingSite = $this->SettingSites->patchEntity($settingSite, $this->getRequest()->getData());

            if ($this->SettingSites->save($settingSite)) {
				$this->Flash->success('Les paramètres ont bien été mis à jour.');
            } else {
				$this->Flash->error('Merci de vérifier les informations saisies');
            }
        }
        $this->set(compact('settingSite', 'durations'));
    }


	public function confirm_img($id, $file = 'image'){
		$this->viewBuilder()->setLayout('ajax');
		if($id)
			$setting_site = $this->SettingSites->find()
			                              ->where(['SettingSites.id'=>$id])
			                              ->select(['id',$file])
			                              ->first();

		$this->set(compact('setting_site','file'));

	}

	public function delete_img($id, $file = 'image'){
		$this->viewBuilder()->setLayout('ajax');

		$img = $this->SettingSites->find()
		                        ->where(['SettingSites.id'=>$id])
		                        ->select([$file])
		                        ->first();

		if($img){
			$post = $this->SettingSites->get($id);
			$post->$file = '';
			if($this->SettingSites->save($post)){
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
