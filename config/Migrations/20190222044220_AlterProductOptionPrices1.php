<?php
use Migrations\AbstractMigration;

class AlterProductOptionPrices1 extends AbstractMigration
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

        $table->addColumn('idx', 'integer', [
            'default' => null,
            'limit' => 5,
            'after' => 'price',
            'null' => true
        ]);

        $table->update();
    }
}
