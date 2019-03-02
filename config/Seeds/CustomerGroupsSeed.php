<?php
use Migrations\AbstractSeed;

/**
 * CustomerGroups seed.
 */
class CustomerGroupsSeed extends AbstractSeed
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
                'name' => 'Normal',
            ],
            [
                'id' => '2',
                'name' => 'Silver',
            ],
            [
                'id' => '3',
                'name' => 'Gold',
            ],
            [
                'id' => '4',
                'name' => 'Platinum',
            ], 
        ];

        $table = $this->table('customer_groups');
        $table->insert($data)->save();
    }
}
