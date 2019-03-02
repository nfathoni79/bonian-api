<?php
use Migrations\AbstractMigration;

class CreateTableCustomerBuyGroupDetails extends AbstractMigration
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
        $table = $this->table('customer_buy_group_details');
        $table->addColumn('customer_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('customer_buy_group_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
        ]);
        $table->addIndex('customer_buy_group_id');
        $table->addForeignKey('customer_buy_group_id', 'customer_buy_groups', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('customer_id');
        $table->addForeignKey('customer_id', 'customers', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
