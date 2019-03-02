<?php
use Migrations\AbstractMigration;

class CreateTableCustomerPointRates extends AbstractMigration
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
        $table = $this->table('customer_point_rates');
        $table->addColumn('point', 'integer', [
            'default' => 1,
            'limit' => 11,
        ]);
        $table->addColumn('value', 'float', [
            'default' => 1,
        ]);
        $table->create();
    }
}
