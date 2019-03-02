<?php
use Migrations\AbstractMigration;

class CreateTableCustomerBuyGroups extends AbstractMigration
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
        $table = $this->table('customer_buy_groups');
        $table->addColumn('product_group_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('customer_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 5
        ]);
        $table->addIndex('product_group_id');
        $table->addForeignKey('product_group_id', 'product_groups', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('customer_id');
        $table->addForeignKey('customer_id', 'customers', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
