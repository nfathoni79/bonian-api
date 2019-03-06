<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PriceSettingDetailsFixture
 *
 */
class PriceSettingDetailsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'price_setting_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'sku' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'product_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'product_option_price_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'type' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => 'varian', 'collate' => 'latin1_swedish_ci', 'comment' => 'main, varian', 'precision' => null, 'fixed' => null],
        'price' => ['type' => 'decimal', 'length' => 10, 'precision' => 0, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'status' => ['type' => 'integer', 'length' => 1, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '0, waiting, 1 : success, 2: canceled', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'price_setting_id' => ['type' => 'index', 'columns' => ['price_setting_id'], 'length' => []],
            'product_id' => ['type' => 'index', 'columns' => ['product_id'], 'length' => []],
            'product_option_price_id' => ['type' => 'index', 'columns' => ['product_option_price_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'price_setting_details_ibfk_1' => ['type' => 'foreign', 'columns' => ['price_setting_id'], 'references' => ['price_settings', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'price_setting_details_ibfk_2' => ['type' => 'foreign', 'columns' => ['product_id'], 'references' => ['products', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'price_setting_details_ibfk_3' => ['type' => 'foreign', 'columns' => ['product_option_price_id'], 'references' => ['product_option_prices', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
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
                'price_setting_id' => 1,
                'sku' => 'Lorem ipsum dolor sit amet',
                'product_id' => 1,
                'product_option_price_id' => 1,
                'type' => 'Lorem ip',
                'price' => 1.5,
                'status' => 1
            ],
        ];
        parent::init();
    }
}
