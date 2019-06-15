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
namespace App\Controller\V1\Web;

use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\Http\Client\FormData;
use Cake\I18n\Time;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

/**
 * Customers controller
 *
 * @property \App\Model\Table\CustomerWishesTable $CustomerWishes
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class AttachmentsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    /**
     * list all address
     */
    public function index()
    {
        $this->set(compact('data'));
    }


    public function image()
    {
        $this->request->allowMethod(['post', 'delete']);

        $validator = new Validator();

        $validator->requirePresence('image')
            ->add('image', 'check_mime', [
               'rule' => function($value) {
                    if (!empty($value['tmp_name'])) {
                        return in_array(mime_content_type($value['tmp_name']), ['image/jpeg', 'image/png', 'image/gif']);
                    }

                    return false;

               },
               'message' => 'mime error'
            ]);

        $error = $validator->errors($this->request->getData());

        if (empty($error)) {
            $error = null;

            $image = $this->request->getData('image');
            $http = new Client();
            $data = new FormData();

            $file = $data->addFile('image', fopen($image['tmp_name'], 'r'));
            $file->filename($image['name']);

            $response = $http->post(Configure::read('mainSite') . '/attachments/image', (string)$data,['headers' => ['Content-Type' => $data->contentType()]]);

            $data = json_decode($response->getBody()->getContents(), true);

        } else {
            $this->response = $this->response->withStatus(406, 'upload error');
        }

        $this->set(compact('error', 'data'));

    }

}
