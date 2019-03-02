<?php
use Migrations\AbstractSeed;

/**
 * OptionTypes seed.
 */
class OptionTypesSeed extends AbstractSeed
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
                'name' => 'Choice',
            ],
            [
                'id' => '2',
                'name' => 'Added',
            ],
        ];

        $table = $this->table('option_types');
        $table->insert($data)->save();
    }
}
