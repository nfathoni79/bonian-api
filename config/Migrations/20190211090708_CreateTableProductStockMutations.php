<?php
use Migrations\AbstractMigration;

class CreateTableProductStockMutations extends AbstractMigration
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
        $table = $this->table('product_stock_mutations');

        $table->addColumn('product_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('branch_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('product_option_value_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('product_stock_mutation_type_id', 'integer', [
            'default' => null,
            'limit' => 3
        ]);
        $table->addColumn('description', 'text', [
            'default' => null,
        ]);
        $table->addColumn('amount', 'float', [
            'default' => null,
        ]);
        $table->addColumn('balance', 'float', [
            'default' => null,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
        ]);
        $table->addIndex('product_id');
        $table->addForeignKey('product_id', 'products', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('branch_id');
        $table->addForeignKey('branch_id', 'branches', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('product_option_value_id');
        $table->addForeignKey('product_option_value_id', 'product_option_values', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('product_stock_mutation_type_id');
        $table->addForeignKey('product_stock_mutation_type_id', 'product_stock_mutation_types', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
