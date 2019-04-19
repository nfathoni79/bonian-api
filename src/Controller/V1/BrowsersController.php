<?php
namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;
use Cake\Utility\Hash;
use Cake\I18n\Time;
use Cake\Utility\Security;
/**
 * Categories Controller
 *
 * @property \App\Model\Table\BrowsersTable $Browsers
 */
class BrowsersController extends Controller
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Browsers');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->request->allowMethod('post');
        if ($user_agent = $this->request->getData('user_agent')) {
            $data = $this->Browsers->newEntity([
                'bid' => Security::randomString(),
                'user_agent' => $user_agent
            ]);
            if (!$this->Browsers->save($data)) {
                $this->setResponse($this->response->withStatus(406, 'Failed to save cookie'));
            }

            $this->set(compact('data'));
        }
    }

}
