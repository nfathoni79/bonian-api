<?php
use Migrations\AbstractMigration;

class CreateTableCustomerBalances extends AbstractMigration
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
        $table = $this->table('customer_balances');
        $table->addColumn('customer_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true
        ]);
        $table->addColumn('balance', 'float', [
            'default' => null,
        ]);
        $table->addColumn('point', 'float', [
            'default' => null,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
        ]);
        $table->addIndex('customer_id');
        $table->addForeignKey('customer_id', 'customers', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
