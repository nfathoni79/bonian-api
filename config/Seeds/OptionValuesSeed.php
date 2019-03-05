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
            [
                'id' => '12',
                'option_id' => '1',
                'name' => 'Silver',
            ],
            [
                'id' => '13',
                'option_id' => '2',
                'name' => 'standard',
            ],
            [
                'id' => '14',
                'option_id' => '1',
                'name' => 'abu abu',
            ],
            [
                'id' => '15',
                'option_id' => '1',
                'name' => 'hijau',
            ],
            [
                'id' => '16',
                'option_id' => '2',
                'name' => 'S',
            ],
            [
                'id' => '17',
                'option_id' => '1',
                'name' => 'pink',
            ],
            [
                'id' => '18',
                'option_id' => '2',
                'name' => '42',
            ],
            [
                'id' => '19',
                'option_id' => '3',
                'name' => '32 pair',
            ],
            [
                'id' => '20',
                'option_id' => '1',
                'name' => 'Coklat',
            ],
            [
                'id' => '21',
                'option_id' => '1',
                'name' => 'Motif Bunga',
            ],
            [
                'id' => '22',
                'option_id' => '1',
                'name' => 'Ungu',
            ],
            [
                'id' => '23',
                'option_id' => '1',
                'name' => 'GOLD',
            ],
            [
                'id' => '24',
                'option_id' => '1',
                'name' => 'Khaki',
            ],
            [
                'id' => '25',
                'option_id' => '2',
                'name' => '30',
            ],
            [
                'id' => '26',
                'option_id' => '2',
                'name' => '31',
            ],
            [
                'id' => '27',
                'option_id' => '2',
                'name' => '32',
            ],
            [
                'id' => '28',
                'option_id' => '1',
                'name' => 'Cream',
            ],
            [
                'id' => '29',
                'option_id' => '2',
                'name' => '40',
            ],
            [
                'id' => '30',
                'option_id' => '2',
                'name' => '41',
            ],
            [
                'id' => '31',
                'option_id' => '2',
                'name' => 'M',
            ],
            [
                'id' => '32',
                'option_id' => '2',
                'name' => 'L',
            ],
            [
                'id' => '33',
                'option_id' => '2',
                'name' => 'XL',
            ],
            [
                'id' => '34',
                'option_id' => '1',
                'name' => 'GRAY',
            ],
            [
                'id' => '35',
                'option_id' => '2',
                'name' => '28',
            ],
            [
                'id' => '36',
                'option_id' => '2',
                'name' => '34',
            ],
            [
                'id' => '37',
                'option_id' => '1',
                'name' => 'NAVY BLUE',
            ],
            [
                'id' => '38',
                'option_id' => '2',
                'name' => '37',
            ],
            [
                'id' => '39',
                'option_id' => '1',
                'name' => 'Kuning',
            ],
            [
                'id' => '40',
                'option_id' => '2',
                'name' => '38',
            ],
            [
                'id' => '41',
                'option_id' => '2',
                'name' => '39',
            ],
            [
                'id' => '42',
                'option_id' => '1',
                'name' => 'Merah-Biru',
            ],
            [
                'id' => '43',
                'option_id' => '1',
                'name' => 'Biru-Hijau',
            ],
        ];

        $table = $this->table('option_values');
        $table->insert($data)->save();
    }
}
