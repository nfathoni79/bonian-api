<?php
use Migrations\AbstractMigration;

class CreateTableOptions extends AbstractMigration
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
        $table = $this->table('options');
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 150,
        ]);
        $table->addColumn('sort_order', 'integer', [
            'default' => null,
            'limit' => 3,
        ]);
        $table->create();
    }
}
