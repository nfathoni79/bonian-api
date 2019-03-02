<?php
use Migrations\AbstractMigration;

class CreateTableCustomers extends AbstractMigration
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
        $table = $this->table('customers');
        $table->addColumn('reffcode', 'string', [
            'default' => null,
            'limit' => 10,
        ]);
        $table->addColumn('refferal_customer_id', 'integer', [
            'default' => null,
            'limit' => 11,
        ]);
        $table->addColumn('email', 'string', [
            'default' => null,
            'limit' => 50,
        ]);
        $table->addColumn('password', 'string', [
            'default' => null,
            'limit' => 255,
        ]);
        $table->addColumn('first_name', 'string', [
            'default' => null,
            'limit' => 40,
        ]);
        $table->addColumn('last_name', 'string', [
            'default' => null,
            'limit' => 30,
        ]);
        $table->addColumn('phone', 'string', [
            'default' => null,
            'limit' => 15,
        ]);
        $table->addColumn('dob', 'date', [
            'default' => null,
        ]);
        $table->addColumn('customer_group_id', 'integer', [
            'default' => 1,
            'limit' => 1,
            'null' => true,
        ]);
        $table->addColumn('customer_status_id', 'integer', [
            'default' => 1,
            'limit' => 1,
            'null' => true,
        ]);
        $table->addColumn('is_verified', 'integer', [
            'default' => null,
            'limit' => 1,
        ]);
        $table->addColumn('platforrm', 'string', [
            'default' => null,
            'limit' => 15,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
        ]);
        $table->create();
    }
}
