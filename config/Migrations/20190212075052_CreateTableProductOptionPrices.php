<?php
use Migrations\AbstractMigration;

class CreateTableProductOptionPrices extends AbstractMigration
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
        $table = $this->table('product_option_prices');
        $table->addColumn('product_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('key_form', 'integer', [
            'default' => null,
            'limit' => 2
        ]);
        $table->addColumn('price', 'float', [
            'default' => 0,
            'comment' => 'additiona price from base price'
        ]);

        $table->addColumn('weight', 'integer', [
            'default' => null,
            'limit' => 5,
            'comment' => 'in gram additional weight from base wight'
        ]);


        $table->addColumn('stock', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('width', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('length', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('height', 'integer', [
            'default' => null,
            'limit' => 11
        ]);

        $table->addIndex('product_id');
        $table->addIndex('key_form');
        $table->addForeignKey('product_id', 'products', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
