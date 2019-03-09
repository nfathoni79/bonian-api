<?php
use Migrations\AbstractMigration;

class CreateTableDigitalDetails extends AbstractMigration
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
        $table = $this->table('digital_details');

        $table->addColumn('digital_id', 'integer', [
            'limit' => 11,
            'null' => false
        ]);
        $table->addColumn('code', 'string', [
            'limit' => 15,
            'null' => false
        ]);
        $table->addColumn('name', 'string', [
            'limit' => 50,
            'null' => false
        ]);
        $table->addColumn('denom', 'float', [
            'default' => 0,
            'null' => true
        ]);
        $table->addColumn('operator', 'string', [
            'limit' => 15,
            'null' => false
        ]);
        $table->addColumn('price', 'float', [
            'default' => 0,
            'null' => false
        ]);
        $table->addColumn('status', 'integer', [
            'limit' => 1,
            'default' => 0,
            'null' => true,
        ]);
        $table->addIndex('digital_id');
        $table->addForeignKey('digital_id', 'digitals', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
