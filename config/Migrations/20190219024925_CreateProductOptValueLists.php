<?php
use Migrations\AbstractMigration;

class CreateProductOptValueLists extends AbstractMigration
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
        $table = $this->table('product_option_value_lists');
        $table->addColumn('product_option_price_id', 'integer', [
            'default' => null,
            'null' => true
        ]);

        $table->addColumn('option_value_id', 'integer', [
            'default' => null,
            'null' => true
        ]);

        $table->addIndex('product_option_price_id');
        $table->addIndex('option_value_id');

        $table->addIndex(['product_option_price_id', 'option_value_id'], ['unique' => true]);

        $table->addForeignKey('product_option_price_id', 'product_option_prices', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addForeignKey('option_value_id', 'option_values', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);

        $table->create();
    }
}
