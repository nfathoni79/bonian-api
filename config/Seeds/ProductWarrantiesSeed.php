<?php
use Migrations\AbstractSeed;

/**
 * ProductWarranties seed.
 */
class ProductWarrantiesSeed extends AbstractSeed
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
                'name' => 'No warranty',
            ],
            [
                'id' => '2',
                'name' => '6 months official warranty',
            ],
            [
                'id' => '3',
                'name' => '8 months official warranty',
            ],
            [
                'id' => '4',
                'name' => '15 months official warranty',
            ],
            [
                'id' => '5',
                'name' => '18 months official warranty',
            ],
            [
                'id' => '6',
                'name' => '1 year official warranty',
            ],
            [
                'id' => '7',
                'name' => '2 year official warranty',
            ],
            [
                'id' => '8',
                'name' => '3 year official warranty',
            ],
            [
                'id' => '9',
                'name' => '4 year official warranty',
            ],
            [
                'id' => '10',
                'name' => '5 year official warranty',
            ],
            [
                'id' => '11',
                'name' => '30 days shop warranty',
            ],
            [
                'id' => '12',
                'name' => '1 year shop warranty',
            ],
            [
                'id' => '13',
                'name' => '2 year shop warranty',
            ],
            [
                'id' => '14',
                'name' => '3 year shop warranty',
            ],
            [
                'id' => '15',
                'name' => '4 year shop warranty',
            ],
            [
                'id' => '16',
                'name' => '5 year shop warranty',
            ],
            [
                'id' => '17',
                'name' => '1 year compressor warranty',
            ],
            [
                'id' => '18',
                'name' => '2 year compressor warranty',
            ],
            [
                'id' => '19',
                'name' => '3 year compressor warranty',
            ],
            [
                'id' => '20',
                'name' => '5 year compressor warranty',
            ],
            [
                'id' => '21',
                'name' => '10 year compressor warranty',
            ],
            [
                'id' => '22',
                'name' => '1 year motor warranty',
            ],
            [
                'id' => '23',
                'name' => '2 year motor warranty',
            ],
            [
                'id' => '24',
                'name' => '3 year motor warranty',
            ],
            [
                'id' => '25',
                'name' => '5 year motor warranty',
            ],
            [
                'id' => '26',
                'name' => '10 year motor warranty',
            ],
            [
                'id' => '27',
                'name' => 'Lifetime warranty',
            ],
        ];

        $table = $this->table('product_warranties');
        $table->insert($data)->save();
    }
}
