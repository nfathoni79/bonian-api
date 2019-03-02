<?php
use Migrations\AbstractMigration;

class CreateTableProductGroups extends AbstractMigration
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
        $table = $this->table('product_groups');
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 15
        ]);
        $table->addColumn('value', 'integer', [
            'default' => null,
            'limit' => 11,
            'comment' => 'qty pembeli'
        ]);
        $table->addColumn('date_start', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('date_end', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('status', 'integer', [
            'default' => null,
            'limit' => 1,
            'comment' => '0, waiting, 1 : running, 2: expired,marking status aktif',
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
        ]);
        $table->create();
    }
}
