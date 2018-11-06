<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Chronos\Chronos;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Filesystem\Folder;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

/**
 * Contacts Controller
 *
 * @property \App\Model\Table\ContactsTable $Contacts
 */
class ContactsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');

    }


    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Security->setConfig('unlockedActions', ['actions']);
    }

    public function isAuthorized($user)
    {
        if (in_array($user['role'], ['admin', 'superadmin'])) {
            return true;
        }
        return false;
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        if ($this->getRequest()->getSession()->check('Temp')) {
            $this->getRequest()->getSession()->delete('Temp');
        }
        $this->set('title', 'Gestion des Contacts');
        $this->paginate = [
            'limit' => 25,
            'fields' => ['id', 'name', 'email', 'created', 'readable'],
            'order' => ['Contacts.created' => 'DESC']
        ];

        foreach ($this->getRequest()->getQuery() as $k => $v) {
            if (!empty($v) && $k != 'page' && $k != 'sort' && $k != 'direction') {
                $this->paginate['conditions']['Contacts.' . $k . ' LIKE'] = '%' . trim($v) . '%';
            }
        }

        try {
            $contacts = $this->paginate($this->Contacts);
        } catch (NotFoundException $e) {
            $this->redirect(['action' => 'index']);
        }

        $this->set(compact('contacts'));
    }

    public function view($id = null)
    {

        $this->getRequest()->getSession()->write('Temp.referer', $this->referer());
        $contact = $this->Contacts->find()
            ->where(['Contacts.id' => $id])
            ->first();

        if ($contact->readable == true) {
            $contact->readable = false;
            $this->Contacts->save($contact);
        }

        $title = 'Détail du message';
        $this->set(compact('contact', 'title'));
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
        $tag = $this->Contacts->get($id);
        if ($this->Contacts->delete($tag)) {
            $this->Flash->success('Le contact a bien été surpprimé');

        } else {
            $this->Flash->error('Impossible de supprimer le contact.');
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * @param $id
     */
    public function confirm($id)
    {
        $this->viewBuilder()->setLayout('ajax');
        $this->set('id', $id);

    }


    public function actions()
    {

        if ($this->getRequest()->is('post')) {

            $data = $this->getRequest()->getData();
            $statut = 0;
            if (empty($data['action']) || empty($data['datas'])) {
                $statut = 0;
            }
            $tagIds = Hash::extract($data['datas'], '{n}.value');

            if ($data['action'] == 'delete') {
                foreach ($tagIds as $pId) {
                    $tagId = $this->Contacts->get($pId);
                    $this->Contacts->delete($tagId);
                }
                $statut = 1;

            }
            echo json_encode($statut);
        }

        exit();
    }


    public function export()
    {

        if (!empty($this->request->getData('start-export'))) {
            $start = Chronos::createFromFormat('d/m/Y H:i', $this->request->getData('start-export'))->format('Y-m-d H:i:s');
        } else {
            $start = (new Time(new Chronos()))->format('Y-m-d H:i:s');
        }

        if (!empty($this->request->getData('end-export'))) {
            $end = Chronos::createFromFormat('d/m/Y H:i', $this->request->getData('end-export'))->format('Y-m-d H:i:s');
        } else {
            $end = (new Time(new Chronos()))->format('Y-m-d H:i:s');
        }

        $results = $this->Contacts->find()
            ->orderDesc('Contacts.created')
            ->where(function ($q) use ($start, $end) {
                $q->between('Contacts.created', $start, $end);
                return $q;
            })
            ->all();


        $spreadsheet = new Spreadsheet();

        try {
            $sheet = $spreadsheet->getActiveSheet();

            $row = 1;
            $spreadsheet->getActiveSheet()->getStyle('A' . $row)
                ->getFont()->setSize(14)->setBold(true);


            $sheet->setCellValue('A' . ($row), 'Demandes de contact du ' . $start . ' au ' . $end); // ligne  1

            $row += 1;

            $sheet
                ->setCellValue('A' . $row, 'Id')
                ->setCellValue('B' . $row, 'Civilité')
                ->setCellValue('C' . $row, 'Nom')
                ->setCellValue('D' . $row, 'Prénom')
                ->setCellValue('E' . $row, 'Email')
                ->setCellValue('F' . $row, 'Téléphone')
                ->setCellValue('G' . $row, 'Message')
                ->setCellValue('H' . $row, 'Date')
            ; // ligne 2

            foreach ($results as $key => $result) {

                $row += 1;
                $sheet
                    ->setCellValue('A' . $row, $result->id)
                    ->setCellValue('B' . $row, ($result->civility == 1 ) ? 'M.' : 'Mme')
                    ->setCellValue('C' . $row, $result->name)
                    ->setCellValue('D' . $row, $result->firstname)
                    ->setCellValue('E' . $row, $result->email)
                    ->setCellValueExplicit('F' . $row,  "=MAJUSCULE(" . mb_strtoupper($result->phone) . ")", DataType::TYPE_STRING)
                    ->setCellValue('G' . $row, $result->message)
                    ->setCellValue('H' . $row, $result->created->format('d/m/Y H:i'))
                ;

            }

            $writer = new Csv($spreadsheet);
            $writer->save('export.csv');

        } catch (Exception $e) {
            //  die($e->getMessage());
        }

        $response = $this->response->withFile(
            Configure::read('App.wwwRoot') . DS . 'export.csv',
            ['download' => true, 'name' => "export.csv"]
        );

        return $response;
    }


}
