<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CustomerResetPasswordFixture
 *
 */
class CustomerResetPasswordFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'customer_reset_password';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'customer_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'request_name' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'request_type' => ['type' => 'integer', 'length' => 2, 'unsigned' => false, 'null' => true, 'default' => '1', 'comment' => '1: email, 2: phone', 'precision' => null, 'autoIncrement' => null],
        'otp' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'session_id' => ['type' => 'string', 'length' => 64, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => 'can use session_id or token random string', 'precision' => null, 'fixed' => null],
        'status' => ['type' => 'integer', 'length' => 2, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '0: pending, 1: used', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'customer_id' => ['type' => 'index', 'columns' => ['customer_id'], 'length' => []],
            'request_name' => ['type' => 'index', 'columns' => ['request_name'], 'length' => []],
            'request_type' => ['type' => 'index', 'columns' => ['request_type'], 'length' => []],
            'otp' => ['type' => 'index', 'columns' => ['otp'], 'length' => []],
            'session_id' => ['type' => 'index', 'columns' => ['session_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'otp_2' => ['type' => 'unique', 'columns' => ['otp', 'session_id', 'status'], 'length' => []],
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
                'request_name' => 'Lorem ipsum dolor sit amet',
                'request_type' => 1,
                'otp' => 'Lorem ip',
                'session_id' => 'Lorem ipsum dolor sit amet',
                'status' => 1,
                'created' => '2019-05-24 16:00:01',
                'modified' => '2019-05-24 16:00:01'
            ],
        ];
        parent::init();
    }
}
