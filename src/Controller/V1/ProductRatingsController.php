<?php
namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;
use Cake\Utility\Hash;
use Cake\I18n\Time;
/**
 * Categories Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 * @property \App\Model\Table\ProductRatingsTable $ProductRatings
 * @method \App\Model\Entity\ProductRating[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductRatingsController extends Controller
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Products');
        $this->loadModel('ProductRatings');
    }

    /**
     * @param $product_id
     */
    public function index($product_id)
    {
        $product_ratings = $this->ProductRatings->find()
            ->select([

            ])
            ->contain([
                'Customers' => [
                    'fields' => [
                        'id',
                        'email',
                        'username',
                        'first_name',
                        'last_name',
                    ]
                ]
            ])
            ->where([
                'ProductRatings.product_id' => $product_id
            ]);

        if ($rating = $this->request->getQuery('rating')) {
            if (in_array($rating, [1,2,3,4,5])) {
                $product_ratings->where([
                    'rating' => $rating
                ]);
            }

        }


        $product_ratings
            ->orderDesc('ProductRatings.id');

        $product_ratings = $this->paginate($product_ratings);

        $product_ratings = $product_ratings->map(function(\App\Model\Entity\ProductRating $row) {
            $row->created = $row->created instanceof \Cake\I18n\FrozenTime  ? $row->created->timestamp : (Time::now())->timestamp;
            unset($row->product_id);
            unset($row->customer_id);
            unset($row->modified);
            return $row;
        });

        $this->set(compact('product_ratings'));
    }

}
