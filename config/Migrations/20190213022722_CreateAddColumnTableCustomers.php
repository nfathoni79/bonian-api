<?php
use Migrations\AbstractMigration;

class CreateAddColumnTableCustomers extends AbstractMigration
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
        $table->addColumn('username', 'string', [
            'default' => null,
            'limit' => 30,
            'after' => 'email',
            'null' => false
        ]);
        $table->addIndex('email');
        $table->addIndex('username');
        $table->update();
    }
}
