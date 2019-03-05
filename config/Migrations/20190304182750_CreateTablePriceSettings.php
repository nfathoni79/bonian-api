<?php
use Migrations\AbstractMigration;

class CreateTablePriceSettings extends AbstractMigration
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
        $table = $this->table('price_settings');

        $table->addColumn('user_id', 'integer', [
            'null' => false,
            'limit' => 2
        ]);
        $table->addColumn('schedule', 'date', [
            'null' => false,
        ]);
        $table->addColumn('status', 'integer', [
            'default' => 0,
            'null' => false,
            'limit' => 1,
            'comment' => '0, waiting schedule, 1 : finish schedule, 2: canceled schedule'
        ]);
        $table->addColumn('created', 'datetime', [
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'null' => false,
        ]);

        $table->addIndex('user_id');
        $table->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
