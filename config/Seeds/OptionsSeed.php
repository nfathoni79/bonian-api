<?php
use Migrations\AbstractSeed;

/**
 * Options seed.
 */
class OptionsSeed extends AbstractSeed
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
                'name' => 'Warna',
                'sort_order' => '1',
            ],
            [
                'id' => '2',
                'name' => 'Ukuran',
                'sort_order' => '2',
            ],
            [
                'id' => '3',
                'name' => 'Quantity',
                'sort_order' => '0',
            ],
        ];

        $table = $this->table('options');
        $table->insert($data)->save();
    }
}
