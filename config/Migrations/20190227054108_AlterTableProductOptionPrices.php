<?php
use Migrations\AbstractMigration;

class AlterTableProductOptionPrices extends AbstractMigration
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

        $table->addColumn('sku', 'string', [
            'after' => 'product_id',
            'null' => false,
            'limit' => 50
        ]);
        $table->addColumn('expired', 'date', [
            'default' => null,
            'after' => 'sku',
            'null' => true
        ]);

        $table->update();
    }
}
