<?php
use Migrations\AbstractSeed;

/**
 * OptionValues seed.
 */
class OptionValuesSeed extends AbstractSeed
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
                'option_id' => '1',
                'name' => 'Merah',
            ],
            [
                'id' => '2',
                'option_id' => '1',
                'name' => 'Biru',
            ],
            [
                'id' => '5',
                'option_id' => '1',
                'name' => 'Hitam',
            ],
            [
                'id' => '6',
                'option_id' => '1',
                'name' => 'Putih',
            ],
            [
                'id' => '7',
                'option_id' => '3',
                'name' => '6 PCS',
            ],
            [
                'id' => '8',
                'option_id' => '3',
                'name' => '12 PCS',
            ],
            [
                'id' => '9',
                'option_id' => '3',
                'name' => '24 PCS',
            ],
            [
                'id' => '10',
                'option_id' => '3',
                'name' => 'paket voucher 2 pcs',
            ],
            [
                'id' => '11',
                'option_id' => '3',
                'name' => 'paket voucher 4 pcs',
            ],
        ];

        $table = $this->table('option_values');
        $table->insert($data)->save();
    }
}
