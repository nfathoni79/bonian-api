<?php
use Migrations\AbstractSeed;

/**
 * Users seed.
 */
class UsersSeed extends AbstractSeed
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
                'email' => 'admin@admin.com',
                'password' => '$2y$10$kKYlt.dPlQkpINQMmVrZGeV.nIh6P5mrHEHq6Ms56heAfhRWe2d9m',
                'first_name' => 'Superadmin',
                'last_name' => 'Zolaku',
                'group_id' => '1',
                'user_status_id' => '1',
                'branch_id' => NULL,
                'created' => '2019-01-10 05:05:01',
                'modified' => '2019-01-10 05:05:01',
            ],
        ];

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
