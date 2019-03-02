<?php
use Migrations\AbstractMigration;

class AlterTableCustomers extends AbstractMigration
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

        $table->addColumn('activation', 'string', [
            'default' => null,
            'limit' => 255,
            'after' => 'is_verified',
            'null' => true
        ]);
        $table->addIndex('customer_group_id');
        $table->addForeignKey('customer_group_id', 'customer_groups', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('customer_status_id');
        $table->addForeignKey('customer_status_id', 'customer_statuses', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->update();
    }
}
