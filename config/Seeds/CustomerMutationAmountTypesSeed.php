<?php
use Migrations\AbstractSeed;

/**
 * CustomerMutationAmountTypes seed.
 */
class CustomerMutationAmountTypesSeed extends AbstractSeed
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
                'name' => 'Deposite',
                'type' => 'Credit',
            ],
        ];

        $table = $this->table('customer_mutation_amount_types');
        $table->insert($data)->save();
    }
}
