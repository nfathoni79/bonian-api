<?php
use Migrations\AbstractMigration;

class CreateTableOptionValues extends AbstractMigration
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
        $table = $this->table('option_values');
        $table->addColumn('option_id', 'integer', [
            'default' => null,
            'limit' => 9,
        ]);
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 150,
        ]);
        $table->addIndex('option_id');
        $table->addForeignKey('option_id', 'options', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
