<?php
use Migrations\AbstractMigration;

class CreateTableGameWheels extends AbstractMigration
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
        $table = $this->table('game_wheels');

        $table->addColumn('product_name', 'string', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('probability', 'float', [
            'default' => 0,
            'null' => true
        ]);
        $table->create();
    }
}
