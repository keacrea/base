<?php
/**
 * Created by PhpStorm.
 * User: PC keacrea
 * Date: 02/02/2018
 * Time: 14:53
 */

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Network\Session;
use Cake\Routing\Router;

class SaveCloseComponent extends Component
{

    public $controller = null;
    public $session = null;

    public function initialize(array $config)
    {
        parent::initialize($config);
        /**
         * Get current controller & session
         */
        $this->controller = $this->_registry->getController();

        $this->session = $this->controller->getRequest()->getSession();

    }


    public function redirect($id = null)
    {

        $url = Router::url(['controller' => $this->controller->getName(), 'action' => 'index', 'prefix' => 'admin']);

        if ($this->controller->request->getData('save_close')) {
            if ($this->session->check('Temp') && strpos($this->session->read('Temp'), $url) !== false) {
                $this->controller->redirect($this->session->read('Temp'));
            } else {
                $this->controller->redirect(['controller' => $this->controller->getName(), 'action' => 'index']);
            }
        } else {
            $this->controller->redirect(['controller' => $this->controller->getName(), 'action' => 'edit', $id]);
        }

    }
}
