<?php
use Migrations\AbstractSeed;

/**
 * CustomerStatuses seed.
 */
class CustomerStatusesSeed extends AbstractSeed
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
                'name' => 'Active',
            ],
            [
                'id' => '2',
                'name' => 'Blocked',
            ],
            [
                'id' => '3',
                'name' => 'Pending',
            ],
        ];

        $table = $this->table('customer_statuses');
        $table->insert($data)->save();
    }
}
