<?php
use Migrations\AbstractSeed;

/**
 * Branches seed.
 */
class BranchesSeed extends AbstractSeed
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
                'name' => 'Jakarta',
                'address' => 'Jakarta',
                'phone' => '0219948433',
                'provice_id' => '6',
                'city_id' => '153',
                'subdistrict_id' => '2109',
                'latitude' => '0.66556',
                'longitude' => '7.12555',
                'created' => '2019-02-23 12:04:05',
                'modified' => '2019-02-23 12:04:05',
            ],
            [
                'id' => '2',
                'name' => 'Bandung',
                'address' => 'Bandung',
                'phone' => '0',
                'provice_id' => '9',
                'city_id' => '23',
                'subdistrict_id' => '346',
                'latitude' => '0',
                'longitude' => '0',
                'created' => '2019-03-05 11:09:42',
                'modified' => '2019-03-05 11:09:42',
            ],
            [
                'id' => '3',
                'name' => 'Semarang',
                'address' => 'Semarang',
                'phone' => '0',
                'provice_id' => '10',
                'city_id' => '398',
                'subdistrict_id' => '5479',
                'latitude' => '0',
                'longitude' => '0',
                'created' => '2019-03-05 11:10:23',
                'modified' => '2019-03-05 11:10:23',
            ],
        ];

        $table = $this->table('branches');
        $table->insert($data)->save();
    }
}
