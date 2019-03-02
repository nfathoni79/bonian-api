<?php
use Migrations\AbstractMigration;

class CreateTableArosAcos extends AbstractMigration
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
        $table = $this->table('aros_acos');
        $table->addColumn('aro_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('aco_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('_create', 'string', [
            'default' => null,
            'limit' => 2
        ]);
        $table->addColumn('_read', 'string', [
            'default' => null,
            'limit' => 2
        ]);
        $table->addColumn('_update', 'string', [
            'default' => null,
            'limit' => 2
        ]);
        $table->addColumn('_delete', 'string', [
            'default' => null,
            'limit' => 2
        ]);
        $table->addIndex('aro_id');
        $table->addIndex('aco_id');
        $table->create();
    }
}
