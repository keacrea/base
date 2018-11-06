<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Network\Exception\NotFoundException;


/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    /**
     * @throws \Exception
     */
    public function initialize(){
        parent::initialize();
        $this->loadComponent('Paginator');

    }

    public function beforeFilter(Event $event){
        parent::beforeFilter($event);
        $this->Auth->allow(['login','forgot','resetPassword']);
        $this->Cookie->setConfig([
            'expires' => '+1 year'
        ]);
    }

    public function isAuthorized($user)
    {
        if(in_array($user['role'],['admin','superadmin'])){
            return true;
        }
        return false;
    }


    public function login()
    {
        if ($this->getRequest()->getSession()->check('Auth.User.id')){
            return $this->redirect(['controller' => 'Pages' , 'action' => 'index']);
        }

        $this->viewBuilder()->setLayout('login');
        if($this->getRequest()->is('post')){

            $user = $this->Auth->identify();
            if($user) {
                $this->Auth->setUser($user);
                if ($this->getRequest()->getData('remember') == 1) {
                    $this->saveToken($this->getRequest()->getData('mail'));
                }
                $this->redirect(['controller'=>'Users','action'=>'index','prefix'=>'admin']);

            }else{
                $this->Flash->error('Vos identifiants sont incorrects');
            }
        }

    }

    private function saveToken($mail){
        $userId = $this->Users->find()->where(['mail'=>$mail])->select(['id'])->first();
        $userId->token = md5(uniqid(rand()));
        if($this->Users->save($userId)){
            $this->Cookie->write('remember',$userId->token);
        }
        return true;
    }

    private function deleteToken($User_id){
        $User = $this->Users->get($User_id);
        $User->token = '';
        if($this->Users->save($User)){
            $this->Cookie->delete('remember');
        }
        return true;
    }

    public function logout(){
        if($this->Cookie->check('remember')){
            $this->deleteToken($this->getRequest()->getSession()->read('Auth.User.id'));
        }
        $this->Flash->success('Vous êtes maintenant déconnecté.');
        return $this->redirect($this->Auth->logout());
    }

    public function index(){

        $this->paginate = [
            'limit' => 25,
            'fields' => [
                'id',
                'name',
                'mail'
            ],
            'order' => [
                'Users.id' => 'ASC'
            ]
        ];

        if ($this->getRequest()->getQuery('search')) {
            foreach ($this->getRequest()->getQuery() as $k => $v) {
                if (!empty($v) && $k != 'page' && $k != 'sort' && $k != 'direction' && $k != 'search') {
                    $this->paginate['conditions']['Users.' . $k . ' LIKE'] = '%' . trim($v) . '%';
                }
            }
        }

        try {
            $users = $this->paginate($this->Users);
        } catch (NotFoundException $e) {
            $this->redirect(['action' => 'index']);
        }

        $this->set('users',$users);
    }

    public function add(){

        $user = $this->Users->newEntity();
        if($this->getRequest()->is('post')){
            $this->Users->patchEntity($user, $this->getRequest()->getData());
            if($this->Users->save($user)){
                $this->Flash->success('l\'administrateur à bien été ajouté');
                return $this->redirect(['action' => 'index']);
            }else{
                $this->Flash->error('Merci de vérifier les informations saisies');
            }
        }
        $this->set(compact('user'));
    }

    public function edit($id = null){
        if(!$id || !$this->Users->exists(['id'=>$id])){
            $this->Flash->error('Cet administrateur n\'existe pas');
            $this->redirect(['action'=>'index']);
        }

        $user = $this->Users->get($id);
        if($this->getRequest()->is(['patch','post','put'])){
            $this->Users->patchEntity($user, $this->getRequest()->getData());
            if($this->Users->save($user)){
                $this->Flash->success('Votre profil a bien été mis à jour');
                return $this->redirect(['action' => 'index']);
            }else{
                $this->Flash->error('Merci de vérifier les informations saisies');
            }
        }
        $this->set(compact('user'));
    }

    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success('L\'administrateur a bien été surpprimé');
        } else {
            $this->Flash->error('Impossible de supprimer l\'administrateur.');
        }
        return $this->redirect(['action' => 'index']);
    }
    /**
     * @param $id
     */
    public function confirm($id){
        $this->viewBuilder()->setLayout('ajax');
        $this->set('id',$id);

    }


    public function forgot(){
        $this->viewBuilder()->setLayout('login');
        if($this->getRequest()->is('post')){

            $userId = $this->Users->find()
                ->where(['mail'=>$this->getRequest()->getData('mail')])
                ->andWhere(['role IN'=>['superadmin', 'admin']])
                ->select(['id'])->first();
            if(is_null($userId)){
                $this->Flash->error('Ce mail n\'est associé à aucun compte');

            }else{
                $token = md5(uniqid().time());
                $user = $this->Users->newEntity();
                $user->id = $userId->id;
                $user->token = $token;
                if($this->Users->save($user)){
                    $email = new Email('default');
                    $email->setFrom(['no-reply@mail.com' => 'Administration'])
                        ->setTo($this->getRequest()->getData('mail'))
                        ->setSubject('Mot de passe oublié')
                        ->setTemplate('default','default')
                        ->setEmailFormat('html')
                        ->setViewVars([
                            'title'=>'Générer un nouveau mot de passe',
                            'token' => $token,
                            'id' => $userId->id
                        ])
                        ->send();

                    $this->Flash->success('Un email vous a été envoyé avec les instructions pour générer votre mot de passe.');
                    $this->redirect(['action' => 'login']);
                }else{
                    $this->Flash->error('Un problème empèche l\'envoi du mail.');
                }
            }
        }
    }

    public function resetPassword($user_id, $token){
        $this->viewBuilder()->setLayout('login');
        $user = $this->Users->find()
            ->select(['id','token'])
            ->where(['Users.id'=>$user_id,'Users.token'=>$token])
            ->first();
        if(is_null($user)){
            $this->Flash->error('Ce lien ne semble pas bon');
            $this->redirect(['action' => 'forgot']);
        }

        if($this->getRequest()->is('post')){

            $userValid = $this->Users->get($user->id);
            if ($this->getRequest()->is(['patch', 'post', 'put'])) {
                $userValid->token = '';
                $userValid = $this->Users->patchEntity($userValid, $this->getRequest()->getData());
                if ($this->Users->save($userValid)) {
                    $this->Flash->success('Le mot de passe a bien été réinitialisé.');
                    return $this->redirect(['action' => 'login']);
                } else {
                    $this->Flash->error('Merci de vérifier les informations saisies');
                }
            }
        }

    }



}
