<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\TableRegistry;


/**
 * Contacts Controller
 *
 * @property \App\Model\Table\ContactsTable $Contacts
 */
class ContactsController extends AppController
{
    use MailerAwareTrait;

    public function initialize()
    {
        parent::initialize();

    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {

        $pageTable = TableRegistry::getTableLocator()->get('Pages');
        $page = $pageTable->findPageStatic($this->getRequest()->getParam('controller'),$this->getRequest()->getParam('action'));
        $settings_mail = TableRegistry::getTableLocator()->get('SettingSites')->SettingMail();

        $contact = $this->Contacts->newEntity();


        if ($this->getRequest()->is('post')) {

            $contact = $this->Contacts->patchEntity($contact, $this->getRequest()->getData());

            if ($this->Contacts->save($contact)) {

                $this->getMailer('User')->send('admin', ['Une demande de contact ', $contact, $settings_mail]);
                $this->getRequest()->getSession()->write('contact', $contact->id);
                $this->redirect(['action' => 'confirm']);

            }else{
                $this->Flash->error_user('Merci de vérifier les informations saisies.');
            }
        }

        $parents = $pageTable->find('path', ['for' => $page->id])
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


        $this->set(compact('page','contact', 'parents'));
    }

    public function confirm()
    {
        if (!$this->getRequest()->getSession()->check('contact')) {
            return $this->redirect('/');
        }


        $pageTable = TableRegistry::getTableLocator()->get('Pages');
        $page = $pageTable->findPageStatic($this->getRequest()->getParam('controller'), $this->getRequest()->getParam('action'));

        $contact = $this->Contacts->find()
//            ->select(['name'])
            ->where(['id' => $this->getRequest()->getSession()->read('contact')])
            ->first()
        ;

        $this->getRequest()->getSession()->delete('contact');

        $this->set(compact('page', 'contact'));

    }


}
