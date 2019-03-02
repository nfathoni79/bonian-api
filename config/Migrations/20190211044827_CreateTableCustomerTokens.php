<?php
use Migrations\AbstractMigration;

class CreateTableCustomerTokens extends AbstractMigration
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
        $table = $this->table('customer_tokens');
//        customer_id	uuid	activated	ip	created	modified
        $table->addColumn('customer_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true
        ]);
        $table->addColumn('uuid', 'string', [
            'default' => null,
            'limit' => 255,
        ]);
        $table->addColumn('activated', 'integer', [
            'default' => null,
            'limit' => 1,
        ]);
        $table->addColumn('ip', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => true
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => true
        ]);
        $table->addIndex('customer_id');
        $table->addForeignKey('customer_id', 'customers', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
