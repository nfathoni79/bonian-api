<?php
use Migrations\AbstractMigration;

class AlterTableProductDealDetails1 extends AbstractMigration
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
        $table->addColumn('start_stock', 'float', [
            'default' => 0,
            'null' => false,
            'limit' => 5,
            'after' => 'product_id'
        ]);
        $table->update();
    }
}
