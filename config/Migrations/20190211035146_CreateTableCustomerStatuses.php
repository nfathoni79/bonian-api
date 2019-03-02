<?php
use Migrations\AbstractMigration;

class CreateTableCustomerStatuses extends AbstractMigration
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
        $table = $this->table('customer_statuses');
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 10,
            'null' => false,
        ]);
        $table->create();
    }
}
