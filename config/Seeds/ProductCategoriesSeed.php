<?php
use Migrations\AbstractSeed;

/**
 * ProductCategories seed.
 */
class ProductCategoriesSeed extends AbstractSeed
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
                'parent_id' => NULL,
                'lft' => '1',
                'rght' => '16',
                'name' => 'Pakaian Pria',
                'slug' => 'pakaian-pria',
                'description' => 'Aneka pakaian pria',
                'path' => '1547470496114.png',
            ],
            [
                'id' => '2',
                'parent_id' => '1',
                'lft' => '2',
                'rght' => '3',
                'name' => 'Atasan',
                'slug' => 'atasan',
                'description' => 'Atasan pakaian pria',
                'path' => '',
            ],
            [
                'id' => '3',
                'parent_id' => '1',
                'lft' => '4',
                'rght' => '5',
                'name' => 'Bawahan',
                'slug' => 'bawahan',
                'description' => 'Bawahan pakaian pria',
                'path' => '',
            ],
            [
                'id' => '4',
                'parent_id' => '1',
                'lft' => '6',
                'rght' => '7',
                'name' => 'Jeans',
                'slug' => 'jeans',
                'description' => 'Jeans pakaian pria',
                'path' => '',
            ],
            [
                'id' => '5',
                'parent_id' => '1',
                'lft' => '8',
                'rght' => '9',
                'name' => 'Outerwear',
                'slug' => 'outerwear',
                'description' => '-',
                'path' => '',
            ],
            [
                'id' => '6',
                'parent_id' => '1',
                'lft' => '10',
                'rght' => '11',
                'name' => 'Batik',
                'slug' => 'batik',
                'description' => '-',
                'path' => '',
            ],
            [
                'id' => '7',
                'parent_id' => '1',
                'lft' => '12',
                'rght' => '13',
                'name' => 'Pakaian Tidur',
                'slug' => 'pakaian-tidur',
                'description' => '-',
                'path' => '',
            ],
            [
                'id' => '8',
                'parent_id' => '1',
                'lft' => '14',
                'rght' => '15',
                'name' => 'Pakaian Dalam',
                'slug' => 'pakaian-dalam',
                'description' => '-',
                'path' => '',
            ],
            [
                'id' => '9',
                'parent_id' => NULL,
                'lft' => '17',
                'rght' => '18',
                'name' => 'Pakaian Wanita',
                'slug' => 'pakaian-wanita',
                'description' => '-',
                'path' => '1547461907247.png',
            ],
            [
                'id' => '10',
                'parent_id' => NULL,
                'lft' => '19',
                'rght' => '20',
                'name' => 'Handphone & Aksesoris',
                'slug' => 'handphone--aksesoris',
                'description' => '-',
                'path' => '1547461887842.png',
            ],
            [
                'id' => '11',
                'parent_id' => NULL,
                'lft' => '21',
                'rght' => '22',
                'name' => 'Komputer & Aksesoris',
                'slug' => 'komputer--aksesoris',
                'description' => '-',
                'path' => '1547461931955.png',
            ],
            [
                'id' => '12',
                'parent_id' => NULL,
                'lft' => '23',
                'rght' => '24',
                'name' => 'Fashion Bayi & Anak',
                'slug' => 'fashion-bayi--anak',
                'description' => '-',
                'path' => '1547461950438.png',
            ],
            [
                'id' => '13',
                'parent_id' => NULL,
                'lft' => '25',
                'rght' => '26',
                'name' => 'Sepatu Pria',
                'slug' => 'sepatu-pria',
                'description' => '-',
                'path' => '1547461967203.png',
            ],
        ];

        $table = $this->table('product_categories');
        $table->insert($data)->save();
    }
}
