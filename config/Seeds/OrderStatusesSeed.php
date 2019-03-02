<?php
use Migrations\AbstractSeed;

/**
 * OrderStatuses seed.
 */
class OrderStatusesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => '1',
                'name' => 'Pending',
            ],
            [
                'id' => '2',
                'name' => 'Processing',
            ],
            [
                'id' => '3',
                'name' => 'Shipped',
            ],
            [
                'id' => '4',
                'name' => 'Complete',
            ],
            [
                'id' => '5',
                'name' => 'Canceled',
            ],
            [
                'id' => '6',
                'name' => 'Denied',
            ],
            [
                'id' => '7',
                'name' => 'Canceled Reversal',
            ],
            [
                'id' => '8',
                'name' => 'Failed',
            ],
            [
                'id' => '9',
                'name' => 'Refunded',
            ],
            [
                'id' => '10',
                'name' => 'Reversed',
            ],
            [
                'id' => '11',
                'name' => 'Chargeback',
            ],
            [
                'id' => '12',
                'name' => 'Expired',
            ],
            [
                'id' => '13',
                'name' => 'Processed',
            ],
            [
                'id' => '14',
                'name' => 'Voided',
            ],
        ];

        $table = $this->table('order_statuses');
        $table->insert($data)->save();
    }
}
