<?php
use Migrations\AbstractMigration;

class AlterTableProductDealDetails extends AbstractMigration
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
        $table->removeColumn('discount');
        $table->removeColumn('stock');

        $table->addColumn('price_sale', 'float', [
            'default' => null,
            'after' => 'product_id',
            'null' => true
        ]);
        $table->update();
    }
}
