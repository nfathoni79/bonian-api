<?php
use Migrations\AbstractMigration;

class CreateTableBanners extends AbstractMigration
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
        $table = $this->table('banners');

        $table->addColumn('product_category_id', 'integer', [
            'limit' => 11,
            'null' => true
        ]);
        $table->addColumn('name', 'string', [
            'default' => 'null',
            'limit' => 100,
            'null' => true
        ]);
        $table->addColumn('dir', 'string', [
            'default' => 'null',
            'limit' => 255,
            'null' => true
        ]);
        $table->addColumn('size', 'integer', [
            'limit' => 11,
            'null' => true
        ]);
        $table->addColumn('type', 'string', [
            'default' => 'null',
            'limit' => 150,
            'null' => true
        ]);
        $table->addColumn('status', 'integer', [
            'default' => '0',
            'limit' => 1,
            'null' => true
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
        ]);
        $table->create();
    }
}
