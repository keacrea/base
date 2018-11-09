<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Mailer\MailerAwareTrait;
use Cake\Network\Exception\NotFoundException;


/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

    use MailerAwareTrait;

    /**
     * @throws \Exception
     */
    public function initialize(){
        parent::initialize();
        $this->loadComponent('Paginator');

    }

    public function beforeFilter(Event $event){
        parent::beforeFilter($event);
        $this->Auth->allow(['login','forgot','resetPassword', 'verify']);
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
                    $this->saveToken($this->getRequest()->getData('email'));
                }
                $this->redirect(['controller'=>'Users','action'=>'index','prefix'=>'admin']);

            }else{
                $this->Flash->error('Vos identifiants sont incorrects');
            }
        }

    }

    private function saveToken($email){
        $userId = $this->Users->find()->where(['email'=>$email])->select(['id'])->first();
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
                'email'
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

            $user = $this->Users->find()
                ->select(['id'])
                ->where([
                    'email'=>$this->getRequest()->getData('email'),
                    'role IN'=>['superadmin', 'admin']
                ])
                ->first()
            ;
            if(is_null($user)){
                $this->Flash->error('Ce mail n\'est associé à aucun compte');

            }else{
                $token = $this->Users->tokenize($user->id);
                $this->getMailer('User')->send('forgotPassword', [
                    'no-reply@mail.com',
                    $this->getRequest()->getData('email'),
                    $token,
                ]);
                $this->Flash->success('Un email vous a été envoyé avec les instructions pour générer votre mot de passe.');
                $this->redirect(['action' => 'login']);

            }
        }
    }


    public function resetPassword($token)
    {


        $this->viewBuilder()->setLayout('login');
        if($this->getRequest()->is('post')){

            if ($this->getRequest()->is(['patch', 'post', 'put'])) {


                $result = $this->Users->Tokens->verify($token);
                if($result){
                    $user = $this->Users->find()
                        ->where(['id' => $result->foreign_key])
                        ->first()
                    ;
                    $user = $this->Users->patchEntity($user, $this->getRequest()->getData());

                    if ($this->Users->save($user)) {
                        $this->Flash->success('Le mot de passe a bien été réinitialisé.');
                        return $this->redirect(['action' => 'login']);
                    } else {
                        $this->Flash->error('Merci de vérifier les informations saisies');
                    }
                }else{
                    $this->Flash->error('Ce lien ne semble pas bon');
                    $this->redirect(['action' => 'forgot']);
                }
            }
        }

    }



}
