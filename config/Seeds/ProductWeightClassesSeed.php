<?php
use Migrations\AbstractSeed;

/**
 * ProductWeightClasses seed.
 */
class ProductWeightClassesSeed extends AbstractSeed
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
                'name' => 'Gram',
                'unit' => 'g',
            ],
        ];

        $table = $this->table('product_weight_classes');
        $table->insert($data)->save();
    }
}
