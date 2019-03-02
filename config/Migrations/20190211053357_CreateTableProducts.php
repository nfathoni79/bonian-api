<?php
use Migrations\AbstractMigration;

class CreateTableProducts extends AbstractMigration
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
        $table = $this->table('products');
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 255
        ]);
        $table->addColumn('title', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true
        ]);
        $table->addColumn('slug', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true
        ]);
        $table->addColumn('model', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => true
        ]);
        $table->addColumn('code', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => true
        ]);
        $table->addColumn('sku', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => true,
        ]);
        $table->addColumn('qty', 'integer', [
            'default' => 1,
            'limit' => 6
        ]);
        $table->addColumn('product_stock_status_id', 'integer', [
            'default' => null,
            'limit' => 2,
            'null' => true
        ]);
        $table->addColumn('shipping', 'integer', [
            'default' => null,
            'limit' => 1
        ]);
        $table->addColumn('price', 'float', [
            'default' => null
        ]);
        $table->addColumn('price_discount', 'float', [
            'default' => null
        ]);
        $table->addColumn('weight', 'float', [
            'default' => 0,
            'null' => true
        ]);
        $table->addColumn('product_weight_class_id', 'integer', [
            'default' => null,
            'limit' => 2,
            'null' => true
        ]);
        $table->addColumn('product_status_id', 'integer', [
            'default' => null,
            'limit' => 2,
            'null' => true
        ]);
        $table->addColumn('highlight', 'text', [
            'default' => null,
        ]);
        $table->addColumn('condition', 'text', [
            'default' => null,
        ]);
        $table->addColumn('profile', 'text', [
            'default' => null,
        ]);
        $table->addColumn('view', 'integer', [
            'default' => 0,
            'limit' => 5
        ]);
        $table->addColumn('point', 'integer', [
            'default' => 0,
            'limit' => 5
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
        ]);
        $table->addIndex('product_stock_status_id');
        $table->addForeignKey('product_stock_status_id', 'product_stock_statuses', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('product_weight_class_id');
        $table->addForeignKey('product_weight_class_id', 'product_weight_classes', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('product_status_id');
        $table->addForeignKey('product_status_id', 'product_statuses', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
