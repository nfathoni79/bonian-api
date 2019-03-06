<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProductOptionValueListsFixture
 *
 */
class ProductOptionValueListsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'product_option_price_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'option_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'option_value_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'product_option_price_id' => ['type' => 'index', 'columns' => ['product_option_price_id'], 'length' => []],
            'option_value_id' => ['type' => 'index', 'columns' => ['option_value_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'product_option_price_id_2' => ['type' => 'unique', 'columns' => ['product_option_price_id', 'option_value_id'], 'length' => []],
            'product_option_value_lists_ibfk_1' => ['type' => 'foreign', 'columns' => ['product_option_price_id'], 'references' => ['product_option_prices', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'product_option_value_lists_ibfk_2' => ['type' => 'foreign', 'columns' => ['option_value_id'], 'references' => ['option_values', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
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
                'product_option_price_id' => 1,
                'option_id' => 1,
                'option_value_id' => 1
            ],
        ];
        parent::init();
    }
}
