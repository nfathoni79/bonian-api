<?php
use Migrations\AbstractMigration;

class CreateTableProductPromotions extends AbstractMigration
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
        $table = $this->table('product_promotions');
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 50
        ]);
        $table->addColumn('product_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('qty', 'integer', [
            'default' => null,
            'limit' => 11,
            'comment' => 'qty utama'
        ]);
        $table->addColumn('min_qty', 'integer', [
            'default' => null,
            'limit' => 11,
            'comment' => 'minimum pembelian'
        ]);
        $table->addColumn('free_product_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('free_qty', 'integer', [
            'default' => null,
            'limit' => 11,
            'comment' => 'bonus pembelian'
        ]);
        $table->addColumn('date_start', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('date_end', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
        ]);
        $table->addIndex('product_id');
        $table->addForeignKey('product_id', 'products', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('free_product_id');
        $table->addForeignKey('free_product_id', 'products', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
