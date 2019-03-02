<?php
use Migrations\AbstractSeed;

/**
 * Courriers seed.
 */
class CourriersSeed extends AbstractSeed
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
                'name' => 'JNE',
                'code' => 'jne',
                'created' => '2019-02-08 00:00:00',
            ],
            [
                'id' => '2',
                'name' => 'JNT',
                'code' => 'jnt',
                'created' => '2019-02-08 00:00:00',
            ],
            [
                'id' => '3',
                'name' => 'TIKI',
                'code' => 'tiki',
                'created' => '2019-02-08 00:00:00',
            ],
            [
                'id' => '4',
                'name' => 'POS',
                'code' => 'pos',
                'created' => '2019-02-08 00:00:00',
            ],
        ];

        $table = $this->table('courriers');
        $table->insert($data)->save();
    }
}
