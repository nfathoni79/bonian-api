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
namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;
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
class PagesController extends Controller
{

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function home(){

//        public $paginate = [
//            'sortWhiteList' => [
//                'transaction_id', 'amount'
//            ]
//        ];
//        $this->request->allowMethod('get');
//
//        $currency = $this->request->getQuery('currency');
//
//
//        $transactions = $this->TransactionMutations->find()
//            ->select([
//                'TransactionMutations.transaction_id',
//                'TransactionMutations.amount',
//                'TransactionMutations.balance',
//                'TransactionMutations.currency',
//                'Transactions.transaction_type_id',
//                'Transactions.txid',
//                'Transactions.gross',
//                'Transactions.fee',
//                'Transactions.tax',
//                'Transactions.description',
//                'TransactionTypes.name'
//            ])
//            ->contain(['Transactions.TransactionTypes'])
//            ->where([
//                'TransactionMutations.client_id' => $this->Authenticate->getId()
//            ]);
//
//        if ($currency && strlen($currency) == 3) {
//            $transactions->where([
//                'currency' => $currency
//            ]);
//        }
//
//        $transactions
//            ->orderDesc('TransactionMutations.id');
//
//        $data = $this->paginate($transactions);
//
//        $this->set(compact('data'));
    }
}
