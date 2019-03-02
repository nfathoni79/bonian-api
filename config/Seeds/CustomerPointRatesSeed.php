<?php
use Migrations\AbstractSeed;

/**
 * CustomerPointRates seed.
 */
class CustomerPointRatesSeed extends AbstractSeed
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
                'point' => '1',
                'value' => '1',
            ],
        ];

        $table = $this->table('customer_point_rates');
        $table->insert($data)->save();
    }
}
