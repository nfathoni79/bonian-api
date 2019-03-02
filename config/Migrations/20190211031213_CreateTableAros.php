<?php
use Migrations\AbstractMigration;

class CreateTableAros extends AbstractMigration
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
        $table = $this->table('aros');
        $table->addColumn('parent_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true
        ]);
        $table->addColumn('model', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true
        ]);
        $table->addColumn('foreign_key', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true
        ]);
        $table->addColumn('alias', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true
        ]);
        $table->addColumn('lft', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('rght', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addIndex('alias');
        $table->addIndex('lft');
        $table->addIndex('rght');
        $table->create();
    }
}
