<?php
use Migrations\AbstractMigration;

class CreateTablePriceSettingDetails extends AbstractMigration
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
        $table = $this->table('price_setting_details');
        $table->addColumn('price_setting_id', 'integer', [
            'null' => false,
            'limit' => 11
        ]);
        $table->addColumn('sku', 'string', [
            'null' => false,
            'limit' => 50
        ]);
        $table->addColumn('product_id', 'integer', [
            'null' => true,
            'limit' => 11
        ]);
        $table->addColumn('product_option_price_id', 'integer', [
            'null' => true,
            'limit' => 11
        ]);
        $table->addColumn('type', 'string', [
            'default' => 'varian',
            'limit' => 10,
            'comment' => 'main, varian'
        ]);
        $table->addColumn('price', 'decimal', [
            'limit' => 50
        ]);
        $table->addColumn('status', 'integer', [
            'limit' => 1,
            'comment' => '0, waiting, 1 : success, 2: canceled'
        ]);
        $table->addIndex('price_setting_id');
        $table->addIndex('product_id');
        $table->addIndex('product_option_price_id');
        $table->addForeignKey('price_setting_id', 'price_settings', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addForeignKey('product_id', 'products', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addForeignKey('product_option_price_id', 'product_option_prices', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
