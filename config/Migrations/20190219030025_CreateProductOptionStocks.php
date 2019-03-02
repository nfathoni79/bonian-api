<?php
use Migrations\AbstractMigration;

class CreateProductOptionStocks extends AbstractMigration
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
        $table = $this->table('product_option_stocks');

        $table->addColumn('product_id', 'integer', [
            'default' => null,
            'null' => true
        ]);

        $table->addColumn('product_option_price_id', 'integer', [
            'default' => null,
            'null' => true
        ]);

        $table->addColumn('branch_id', 'integer', [
            'default' => null,
            'null' => true
        ]);

        $table->addColumn('weight', 'integer', [
            'limit' => 6,
            'default' => null,
            'null' => true
        ]);

        $table->addColumn('stock', 'integer', [
            'limit' => 6,
            'default' => null,
            'null' => true
        ]);

        $table->addColumn('width', 'integer', [
            'limit' => 6,
            'default' => null,
            'null' => true
        ]);

        $table->addColumn('length', 'integer', [
            'limit' => 6,
            'default' => null,
            'null' => true
        ]);

        $table->addColumn('height', 'integer', [
            'limit' => 6,
            'default' => null,
            'null' => true
        ]);

        $table->addIndex('product_id');
        $table->addIndex('product_option_price_id');
        $table->addIndex('branch_id');

        $table->addForeignKey('product_id', 'products', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addForeignKey('product_option_price_id', 'product_option_prices', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addForeignKey('branch_id', 'branches', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);

        $table->create();
    }
}
