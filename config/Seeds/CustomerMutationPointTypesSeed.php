<?php
use Migrations\AbstractSeed;

/**
 * CustomerMutationPointTypes seed.
 */
class CustomerMutationPointTypesSeed extends AbstractSeed
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
                'name' => 'Pembelanjaan',
                'type' => 'Debit',
            ],
            [
                'id' => '2',
                'name' => 'Refund',
                'type' => 'Credit',
            ],
            [
                'id' => '3',
                'name' => 'Bonus Point',
                'type' => 'Credit',
            ],
            [
                'id' => '4',
                'name' => 'Bonus Point Generasi',
                'type' => 'Credit',
            ],
        ];

        $table = $this->table('customer_mutation_point_types');
        $table->insert($data)->save();
    }
}
