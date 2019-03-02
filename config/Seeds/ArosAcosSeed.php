<?php
use Migrations\AbstractSeed;

/**
 * ArosAcos seed.
 */
class ArosAcosSeed extends AbstractSeed
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
                'aro_id' => '1',
                'aco_id' => '6',
                '_create' => '1',
                '_read' => '1',
                '_update' => '1',
                '_delete' => '1',
            ],
            [
                'id' => '2',
                'aro_id' => '1',
                'aco_id' => '7',
                '_create' => '1',
                '_read' => '1',
                '_update' => '1',
                '_delete' => '1',
            ],
            [
                'id' => '3',
                'aro_id' => '1',
                'aco_id' => '8',
                '_create' => '1',
                '_read' => '1',
                '_update' => '1',
                '_delete' => '1',
            ],
            [
                'id' => '4',
                'aro_id' => '1',
                'aco_id' => '9',
                '_create' => '1',
                '_read' => '1',
                '_update' => '1',
                '_delete' => '1',
            ],
            [
                'id' => '5',
                'aro_id' => '1',
                'aco_id' => '10',
                '_create' => '1',
                '_read' => '1',
                '_update' => '1',
                '_delete' => '1',
            ],
            [
                'id' => '6',
                'aro_id' => '1',
                'aco_id' => '11',
                '_create' => '1',
                '_read' => '1',
                '_update' => '1',
                '_delete' => '1',
            ],
            [
                'id' => '7',
                'aro_id' => '1',
                'aco_id' => '12',
                '_create' => '1',
                '_read' => '1',
                '_update' => '1',
                '_delete' => '1',
            ],
            [
                'id' => '8',
                'aro_id' => '1',
                'aco_id' => '13',
                '_create' => '1',
                '_read' => '1',
                '_update' => '1',
                '_delete' => '1',
            ],
            [
                'id' => '9',
                'aro_id' => '1',
                'aco_id' => '14',
                '_create' => '1',
                '_read' => '1',
                '_update' => '1',
                '_delete' => '1',
            ],
            [
                'id' => '10',
                'aro_id' => '1',
                'aco_id' => '15',
                '_create' => '1',
                '_read' => '1',
                '_update' => '1',
                '_delete' => '1',
            ],
            [
                'id' => '11',
                'aro_id' => '1',
                'aco_id' => '16',
                '_create' => '1',
                '_read' => '1',
                '_update' => '1',
                '_delete' => '1',
            ],
            [
                'id' => '12',
                'aro_id' => '1',
                'aco_id' => '17',
                '_create' => '1',
                '_read' => '1',
                '_update' => '1',
                '_delete' => '1',
            ],
            [
                'id' => '13',
                'aro_id' => '1',
                'aco_id' => '18',
                '_create' => '1',
                '_read' => '1',
                '_update' => '1',
                '_delete' => '1',
            ],
            [
                'id' => '14',
                'aro_id' => '1',
                'aco_id' => '19',
                '_create' => '1',
                '_read' => '1',
                '_update' => '1',
                '_delete' => '1',
            ],
        ];

        $table = $this->table('aros_acos');
        $table->insert($data)->save();
    }
}
