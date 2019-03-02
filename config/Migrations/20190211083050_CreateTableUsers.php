<?php
use Migrations\AbstractMigration;

class CreateTableUsers extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('users');
        $table->addColumn('email', 'string', [
            'default' => null,
            'limit' => 150,
        ]);
        $table->addColumn('password', 'string', [
            'default' => null,
            'limit' => 255,
        ]);
        $table->addColumn('first_name', 'string', [
            'default' => null,
            'limit' => 50,
        ]);
        $table->addColumn('last_name', 'string', [
            'default' => null,
            'limit' => 50,
        ]);
        $table->addColumn('group_id', 'integer', [
            'default' => null,
            'limit' => 11,
        ]);
        $table->addColumn('user_status_id', 'integer', [
            'default' => null,
            'limit' => 11,
        ]);
        $table->addColumn('branch_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
        ]);
        $table->addIndex('email');
        $table->addIndex('user_status_id');
        $table->addForeignKey('user_status_id', 'user_status', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('group_id');
        $table->addForeignKey('group_id', 'groups', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('branch_id');
        $table->addForeignKey('branch_id', 'branches', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
