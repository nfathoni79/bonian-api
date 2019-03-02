<?php
use Migrations\AbstractMigration;

class CreateTableOrderShippingDetails extends AbstractMigration
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
        $table = $this->table('order_shipping_details');
        $table->addColumn('order_detail_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('note', 'text', [
            'default' => null
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null
        ]);
        $table->addIndex('order_detail_id');
        $table->addForeignKey('order_detail_id', 'order_details', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
