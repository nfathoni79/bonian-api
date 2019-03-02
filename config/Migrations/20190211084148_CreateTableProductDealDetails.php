<?php
use Migrations\AbstractMigration;

class CreateTableProductDealDetails extends AbstractMigration
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
        $table = $this->table('product_deal_details');
        $table->addColumn('product_deal_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('product_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('discount', 'float', [
            'default' => null
        ]);
        $table->addColumn('stock', 'float', [
            'default' => null
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null
        ]);
        $table->addIndex('product_deal_id');
        $table->addForeignKey('product_deal_id', 'product_deals', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('product_id');
        $table->addForeignKey('product_id', 'products', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
