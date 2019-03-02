<?php
use Migrations\AbstractMigration;

class CreateTableOrders extends AbstractMigration
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
        $table = $this->table('orders');
        $table->addColumn('invoice', 'string', [
            'default' => null,
            'limit' => 15
        ]);
        $table->addColumn('customer_id', 'integer', [
            'default' => null,
            'limit' => 9
        ]);
        $table->addColumn('address', 'text', [
            'default' => null
        ]);
        $table->addColumn('voucher_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('product_promotion_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('total', 'float', [
            'default' => null
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null
        ]);
        $table->addIndex('customer_id');
        $table->addForeignKey('customer_id', 'customers', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('product_promotion_id');
        $table->addForeignKey('product_promotion_id', 'product_promotions', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
