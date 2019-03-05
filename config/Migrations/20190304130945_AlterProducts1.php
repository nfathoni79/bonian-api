<?php
use Migrations\AbstractMigration;

class AlterProducts1 extends AbstractMigration
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
        $table = $this->table('products');

        $table->addColumn('barcode', 'string', [
            'after' => 'sku',
            'null' => false,
            'limit' => 50
        ]);

        $table->addColumn('supplier_code', 'string', [
            'after' => 'barcode',
            'null' => false,
            'limit' => 50
        ]);

        $table->addIndex(['barcode', 'supplier_code']);


        $table->update();
    }
}
