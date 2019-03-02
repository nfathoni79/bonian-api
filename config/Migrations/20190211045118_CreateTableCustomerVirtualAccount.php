<?php
use Migrations\AbstractMigration;

class CreateTableCustomerVirtualAccount extends AbstractMigration
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
        $table = $this->table('customer_virtual_account');
//        customer_id	va_number	expired_date	status

        $table->addColumn('customer_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true
        ]);
        $table->addColumn('va_number', 'string', [
            'default' => null,
            'limit' => 255,
        ]);
        $table->addColumn('expired_date', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('status', 'enum', [
            'values' => ['Active', 'Expired']
        ]);
        $table->addIndex('customer_id');
        $table->addForeignKey('customer_id', 'customers', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
