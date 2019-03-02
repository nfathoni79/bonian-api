<?php
use Migrations\AbstractMigration;

class CreateTableCustomerMutationAmountTypes extends AbstractMigration
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
        $table = $this->table('customer_mutation_amount_types');
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 50,
        ]);
        $table->addColumn('type', 'enum', [
            'values' => ['Debit', 'Credit']
        ]);
        $table->create();
    }
}
