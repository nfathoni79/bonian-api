<?php
use Migrations\AbstractMigration;

class CreateTableCustomerMutationPoints extends AbstractMigration
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
        $table = $this->table('customer_mutation_points');
        $table->addColumn('customer_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true
        ]);
        $table->addColumn('customer_mutation_point_type_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true
        ]);
        $table->addColumn('description', 'text', [
            'default' => null,
        ]);
        $table->addColumn('amount', 'float', [
            'default' => null,
        ]);
        $table->addColumn('balance', 'float', [
            'default' => null,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
        ]);
        $table->addIndex('customer_id');
        $table->addForeignKey('customer_id', 'customers', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('customer_mutation_point_type_id');
        $table->addForeignKey('customer_mutation_point_type_id', 'customer_mutation_point_types', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);

        $table->create();
    }
}
