<?php
use Migrations\AbstractMigration;

class CreateTableOrderDetailProducts extends AbstractMigration
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
        $table = $this->table('order_detail_products');
        $table->addColumn('order_detail_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('product_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('product_option_value_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('qty', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('price', 'float', [
            'default' => null,
        ]);
        $table->addColumn('total', 'float', [
            'default' => null,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
        ]);
        $table->addIndex('order_detail_id');
        $table->addForeignKey('order_detail_id', 'order_details', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('product_id');
        $table->addForeignKey('product_id', 'products', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('product_option_value_id');
        $table->addForeignKey('product_option_value_id', 'option_values', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
