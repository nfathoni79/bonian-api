<?php
use Migrations\AbstractSeed;

/**
 * ProductStatuses seed.
 */
class ProductStatusesSeed extends AbstractSeed
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
                'name' => 'Publish',
            ],
            [
                'id' => '2',
                'name' => 'Unpublish',
            ],
        ];

        $table = $this->table('product_statuses');
        $table->insert($data)->save();
    }
}
