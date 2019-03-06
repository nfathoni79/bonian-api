<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CustomerAddresesFixture
 *
 */
class CustomerAddresesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'customer_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'province_id' => ['type' => 'integer', 'length' => 4, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'city_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'subdistrict_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'address' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'customer_id' => ['type' => 'index', 'columns' => ['customer_id'], 'length' => []],
            'province_id' => ['type' => 'index', 'columns' => ['province_id'], 'length' => []],
            'city_id' => ['type' => 'index', 'columns' => ['city_id'], 'length' => []],
            'subdistrict_id' => ['type' => 'index', 'columns' => ['subdistrict_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'customer_addreses_ibfk_1' => ['type' => 'foreign', 'columns' => ['customer_id'], 'references' => ['customers', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'customer_addreses_ibfk_2' => ['type' => 'foreign', 'columns' => ['province_id'], 'references' => ['provinces', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'customer_addreses_ibfk_3' => ['type' => 'foreign', 'columns' => ['city_id'], 'references' => ['cities', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'customer_addreses_ibfk_4' => ['type' => 'foreign', 'columns' => ['subdistrict_id'], 'references' => ['subdistricts', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
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
                'customer_id' => 1,
                'province_id' => 1,
                'city_id' => 1,
                'subdistrict_id' => 1,
                'address' => 'Lorem ipsum dolor sit amet',
                'created' => '2019-03-06 05:12:27',
                'modified' => '2019-03-06 05:12:27'
            ],
        ];
        parent::init();
    }
}
