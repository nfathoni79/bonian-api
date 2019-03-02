<?php
use Migrations\AbstractSeed;

/**
 * GameWheels seed.
 */
class GameWheelsSeed extends AbstractSeed
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
                'product_name' => 'Product 1',
                'probability' => '20',
            ],
            [
                'id' => '2',
                'product_name' => 'Try Again',
                'probability' => '10',
            ],
            [
                'id' => '3',
                'product_name' => 'Product 2',
                'probability' => '20',
            ],
            [
                'id' => '4',
                'product_name' => 'Zonk',
                'probability' => '12.5',
            ],
            [
                'id' => '5',
                'product_name' => 'Get 50 Point',
                'probability' => '6.667',
            ],
            [
                'id' => '6',
                'product_name' => 'Try Again',
                'probability' => '10',
            ],
            [
                'id' => '7',
                'product_name' => 'Product 3',
                'probability' => '3.333',
            ],
            [
                'id' => '8',
                'product_name' => 'Zonk',
                'probability' => '12.5',
            ],
            [
                'id' => '9',
                'product_name' => 'Get 100 Point',
                'probability' => '3.333',
            ],
            [
                'id' => '10',
                'product_name' => 'Product 4',
                'probability' => '1',
            ],
            [
                'id' => '11',
                'product_name' => 'Get 1000 Point',
                'probability' => '0.5',
            ],
            [
                'id' => '12',
                'product_name' => 'Product 5',
                'probability' => '0.5',
            ],
            [
                'id' => '13',
                'product_name' => 'Jackpot',
                'probability' => '0.01',
            ],
        ];

        $table = $this->table('game_wheels');
        $table->insert($data)->save();
    }
}
