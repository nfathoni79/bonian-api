<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrderDetailsFixture
 *
 */
class OrderDetailsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'order_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'branch_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'courrier_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'awb' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'courrier_code' => ['type' => 'string', 'length' => 5, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'origin_subdistrict_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'destination_subdistrict_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'origin_city_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'destination_city_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'product_price' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'shipping_cost' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'total' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'order_status_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'order_id' => ['type' => 'index', 'columns' => ['order_id'], 'length' => []],
            'branch_id' => ['type' => 'index', 'columns' => ['branch_id'], 'length' => []],
            'courrier_id' => ['type' => 'index', 'columns' => ['courrier_id'], 'length' => []],
            'origin_subdistrict_id' => ['type' => 'index', 'columns' => ['origin_subdistrict_id'], 'length' => []],
            'destination_subdistrict_id' => ['type' => 'index', 'columns' => ['destination_subdistrict_id'], 'length' => []],
            'origin_city_id' => ['type' => 'index', 'columns' => ['origin_city_id'], 'length' => []],
            'destination_city_id' => ['type' => 'index', 'columns' => ['destination_city_id'], 'length' => []],
            'order_status_id' => ['type' => 'index', 'columns' => ['order_status_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'order_details_ibfk_1' => ['type' => 'foreign', 'columns' => ['order_id'], 'references' => ['orders', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'order_details_ibfk_2' => ['type' => 'foreign', 'columns' => ['branch_id'], 'references' => ['branches', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'order_details_ibfk_3' => ['type' => 'foreign', 'columns' => ['courrier_id'], 'references' => ['courriers', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'order_details_ibfk_4' => ['type' => 'foreign', 'columns' => ['origin_subdistrict_id'], 'references' => ['subdistricts', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'order_details_ibfk_5' => ['type' => 'foreign', 'columns' => ['destination_subdistrict_id'], 'references' => ['subdistricts', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'order_details_ibfk_6' => ['type' => 'foreign', 'columns' => ['origin_city_id'], 'references' => ['cities', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'order_details_ibfk_7' => ['type' => 'foreign', 'columns' => ['destination_city_id'], 'references' => ['cities', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'order_details_ibfk_8' => ['type' => 'foreign', 'columns' => ['order_status_id'], 'references' => ['order_statuses', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'order_id' => 1,
                'branch_id' => 1,
                'courrier_id' => 1,
                'awb' => 'Lorem ipsum dolor sit amet',
                'courrier_code' => 'Lor',
                'origin_subdistrict_id' => 1,
                'destination_subdistrict_id' => 1,
                'origin_city_id' => 1,
                'destination_city_id' => 1,
                'product_price' => 1,
                'shipping_cost' => 1,
                'total' => 1,
                'order_status_id' => 1,
                'created' => '2019-03-06 05:12:35',
                'modified' => '2019-03-06 05:12:35'
            ],
        ];
        parent::init();
    }
}
