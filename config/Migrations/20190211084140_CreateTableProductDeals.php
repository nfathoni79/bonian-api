<?php
use Migrations\AbstractMigration;

class CreateTableProductDeals extends AbstractMigration
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
        $table = $this->table('product_deals');
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 15,
            'comment' => 'event name'
        ]);
        $table->addColumn('date_start', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('date_end', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('status', 'integer', [
            'default' => 0,
            'limit' => 1,
            'comment' => '0, waiting, 1 : running, 2: expired,marking status aktif'
        ]);
        $table->create();
    }
}
