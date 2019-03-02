<?php
use Migrations\AbstractMigration;

class AlterProductOptionValueLists extends AbstractMigration
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

        $table->addColumn('option_id', 'integer', [
            'default' => null,
            'after' => 'product_option_price_id',
            'null' => true
        ]);

        $table->update();
    }
}
