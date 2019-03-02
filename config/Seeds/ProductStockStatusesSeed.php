<?php
use Migrations\AbstractSeed;

/**
 * ProductStockStatuses seed.
 */
class ProductStockStatusesSeed extends AbstractSeed
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
                'name' => 'In Stock',
            ],
            [
                'id' => '2',
                'name' => 'Out Of Stock',
            ],
        ];

        $table = $this->table('product_stock_statuses');
        $table->insert($data)->save();
    }
}
