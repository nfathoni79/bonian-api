<?php
use Migrations\AbstractMigration;

class CreateTableAuthCodes extends AbstractMigration
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
        $table = $this->table('auth_codes');

        $table->addColumn('phone', 'string', [
            'default' => null,
            'limit' => 15,
        ]);
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 50,
        ]);
        $table->addColumn('code', 'string', [
            'default' => null,
            'limit' => 8,
            'null' => true
        ]);
        $table->addColumn('used', 'integer', [
            'default' => 0,
            'limit' => 1,
            'null' => true
        ]);
        $table->addColumn('expired', 'datetime', [
            'default' => null,
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

        $table->create();
    }
}
