<?php
use Migrations\AbstractMigration;

class CreateTableOrderDetails extends AbstractMigration
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
        $table = $this->table('order_details');
        $table->addColumn('order_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('branch_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('courrier_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('awb', 'string', [
            'default' => null,
            'limit' => 50
        ]);
        $table->addColumn('courrier_code', 'string', [
            'default' => null,
            'limit' => 5
        ]);
        $table->addColumn('origin_subdistrict_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('destination_subdistrict_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('origin_city_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('destination_city_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('product_price', 'float', [
            'default' => null
        ]);
        $table->addColumn('shipping_cost', 'float', [
            'default' => null
        ]);
        $table->addColumn('total', 'float', [
            'default' => null
        ]);
        $table->addColumn('order_status_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
        ]);
        $table->addIndex('order_id');
        $table->addForeignKey('order_id', 'orders', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('branch_id');
        $table->addForeignKey('branch_id', 'branches', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('courrier_id');
        $table->addForeignKey('courrier_id', 'courriers', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('origin_subdistrict_id');
        $table->addForeignKey('origin_subdistrict_id', 'subdistricts', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('destination_subdistrict_id');
        $table->addForeignKey('destination_subdistrict_id', 'subdistricts', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('origin_city_id');
        $table->addForeignKey('origin_city_id', 'cities', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('destination_city_id');
        $table->addForeignKey('destination_city_id', 'cities', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('order_status_id');
        $table->addForeignKey('order_status_id', 'order_statuses', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
